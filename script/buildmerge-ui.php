#!/usr/bin/env php
<?php
/**
 * buildmerge-ui.php — hand the spec to the site's source, backlog-ui (the sibling repo).
 *
 *   php script/buildmerge-ui.php
 *
 * Replaces backlog-ui/src/content/backlog-spec/ whole with what the site needs from here:
 *
 *   spec/README.md                 the spec, rendered by backlog-ui's /spec page at build time
 *   schema/<version>/*.schema.json the JSON Schema, served at https://openbacklog.org/schema/
 *   examples.ts                    every file under examples/ (path => text), for /examples
 *   whitepaper.ts                  the whitepaper as HTML, one entry per section (2.1.13)
 *   source.ts                      which commit of this repo the copy came from
 *
 * and backlog-ui/public/whitepaper/ whole with the PDFs (US Letter and A4), which the site
 * serves at /whitepaper/open-backlog-whitepaper-<version>[-a4].pdf.
 *
 * The whitepaper's pages and its PDFs come from the same run, so from the same files: Pandoc
 * renders the numbered sections to HTML (the same citations, from references.bib, as the PDF),
 * and script/build-pdf.sh builds the PDFs. Both need pandoc and typst (brew install pandoc
 * typst). If either fails, backlog-ui is left untouched.
 *
 * Run it whenever the site should see a spec or whitepaper change: then `npx ng serve` in
 * backlog-ui shows it at http://localhost:4200/spec and /whitepaper. Nothing reaches
 * backlog-web or the live site until backlog-ui's own `php script/buildmerge-web.php`, which
 * refuses a copy taken from uncommitted changes here (marked "+dirty"). So: try drafts freely,
 * commit before deploying. whitepaper/drafts/ never counts as a change.
 */

$specRoot = realpath(__DIR__ . '/..');
$ui = realpath($specRoot . '/../backlog-ui');
function say ($s)  { print("buildmerge-ui: $s\n"); }
function fail ($s) { fwrite(STDERR, "buildmerge-ui: FAILED — $s\n"); exit(1); }
if (!$ui) fail("no backlog-ui beside backlog-spec");

$git = 'git -C ' . escapeshellarg($specRoot);
$commit = trim((string)shell_exec("$git rev-parse --short HEAD 2>/dev/null")) ?: 'unknown';
$dirty = trim((string)shell_exec("$git status --porcelain -- spec schema examples whitepaper script/build-pdf.sh ':!whitepaper/drafts' 2>/dev/null")) !== '';
$source = $commit . ($dirty ? '+dirty' : '');

// The whitepaper first: if the PDF or the HTML fails, nothing in backlog-ui has changed yet.
passthru('cd ' . escapeshellarg($specRoot) . ' && script/build-pdf.sh', $rc);
if ($rc !== 0) fail("script/build-pdf.sh failed; backlog-ui is untouched");
$paper = whitepaper($specRoot);
say("whitepaper " . $paper['version'] . ": " . count($paper['sections']) . " sections, " . count($paper['pdf']) . " PDFs");

$out = "$ui/src/content/backlog-spec";
$before = snapshot($out);
remove_tree($out);
@mkdir("$out/spec", 0755, true);
copy("$specRoot/spec/README.md", "$out/spec/README.md") or fail("could not copy spec/README.md");
$n = 0;
foreach (glob("$specRoot/schema/*", GLOB_ONLYDIR) as $dir) {
	if (basename($dir) === 'test') continue;
	@mkdir("$out/schema/" . basename($dir), 0755, true);
	foreach (glob("$dir/*.schema.json") as $f) { copy($f, "$out/schema/" . basename($dir) . '/' . basename($f)); $n++; }
}
// The examples, as one module: path under examples/ => file text, sorted by path.
$examples = array();
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$specRoot/examples", FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) $examples[substr($f->getPathname(), strlen("$specRoot/examples") + 1)] = file_get_contents($f->getPathname());
ksort($examples);
file_put_contents("$out/examples.ts",
	"// Written by backlog-spec's script/buildmerge-ui.php: every file under examples/, path => text.\n" .
	"export const EXAMPLES: Record<string, string> = " .
	json_encode($examples, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ";\n");

file_put_contents("$out/whitepaper.ts",
	"// Written by backlog-spec's script/buildmerge-ui.php: the whitepaper, rendered by Pandoc from\n" .
	"// whitepaper/[0-9][0-9]-*.md, one entry per section (level-1 heading). Do not edit.\n" .
	"export const WHITEPAPER = " .
	json_encode($paper, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ";\n");

// The PDFs, in public/ (copied into the build as they are, so they download at their URL).
$pdfDir = "$ui/public/whitepaper";
$pdfBefore = snapshot($pdfDir);
remove_tree($pdfDir);
@mkdir($pdfDir, 0755, true);
foreach ($paper['pdf'] as $pdf) copy("$specRoot/dist/" . $pdf['file'], "$pdfDir/" . $pdf['file']) or fail("could not copy " . $pdf['file']);

file_put_contents("$out/source.ts",
	"// Written by backlog-spec's script/buildmerge-ui.php: the backlog-spec commit this copy came\n" .
	"// from, with +dirty if it included uncommitted changes (buildmerge-web.php refuses those).\n" .
	"export const SPEC_COMMIT = '$source';\n");
file_put_contents("$out/README.md",
	"# src/content/backlog-spec/\n\n" .
	"Generated: do not edit. backlog-spec's `script/buildmerge-ui.php` replaces this folder with\n" .
	"the spec text, JSON Schema, examples and whitepaper the site publishes, from backlog-spec\n" .
	"$source. The whitepaper PDFs go to public/whitepaper/.\n");
say("backlog-spec $source -> backlog-ui/src/content/backlog-spec/");
report($before, snapshot($out));
say("backlog-spec $source -> backlog-ui/public/whitepaper/");
report($pdfBefore, snapshot($pdfDir));
if ($dirty) say("note: includes uncommitted changes; fine for ng serve, refused by buildmerge-web.php");
say("next: npx ng serve in backlog-ui to see it; buildmerge-web.php there when ready to deploy");

/**
 * The whitepaper for the site: metadata.yml's title, subtitle, author, version and date; the
 * PDFs script/build-pdf.sh just wrote; and the sections as HTML. Pandoc renders all the
 * numbered sections in one run (so citations resolve against one reference list), and the HTML
 * is cut at each level-1 heading. Section numbers follow the PDF (whitepaper/pdf/whitepaper.lua):
 * Abstract, Executive summary and Appendices are unnumbered, the rest count from 2. Figures are
 * inlined from whitepaper/figures/ (they follow the site's colors), and a link to an anchor
 * becomes a site link to the section that holds it (whitepaper/appendices#ref-…).
 */
function whitepaper ($root)
{
	$meta = array();
	foreach (file("$root/whitepaper/metadata.yml") as $line)
		if (preg_match('/^(title|subtitle|author|version|date): *"?(.*?)"?\s*$/', $line, $m)) $meta[$m[1]] = $m[2];
	foreach (array('title', 'subtitle', 'author', 'version', 'date') as $k)
		if (!isset($meta[$k])) fail("whitepaper/metadata.yml has no $k");

	$pdf = array();
	foreach (array('letter' => '', 'a4' => '-a4') as $size => $suffix) {
		$file = "open-backlog-whitepaper-{$meta['version']}$suffix.pdf";
		if (!is_file("$root/dist/$file")) fail("script/build-pdf.sh did not write dist/$file");
		$pdf[] = array('size' => $size, 'file' => $file, 'bytes' => filesize("$root/dist/$file"));
	}

	$files = glob("$root/whitepaper/[0-9][0-9]-*.md");
	$cmd = 'cd ' . escapeshellarg($root) . ' && pandoc ' . implode(' ', array_map(fn ($f) => escapeshellarg(substr($f, strlen($root) + 1)), $files)) .
		' --metadata-file=whitepaper/metadata.yml --resource-path=whitepaper --citeproc --fail-if-warnings' .
		' -t html5 --wrap=none --syntax-highlighting=none';
	exec($cmd, $lines, $rc);
	if ($rc !== 0) fail("pandoc could not render the whitepaper to HTML");
	$html = implode("\n", $lines);

	// Typeset column widths are for paper; on the site, tables size to their content and scroll.
	$html = preg_replace('#<colgroup>.*?</colgroup>\s*#s', '', $html);
	$html = preg_replace_callback('#<img src="figures/([a-z0-9-]+\.svg)"[^>]*/>#', function ($m) use ($root) {
		$svg = @file_get_contents("$root/whitepaper/figures/{$m[1]}");
		if ($svg === false) fail("whitepaper/figures/{$m[1]} is missing");
		return trim(preg_replace('/^<\?xml[^>]*>\s*/', '', $svg));
	}, $html);

	$parts = preg_split('#(?=<h1 id=")#', $html);
	if (trim(array_shift($parts)) !== '') fail("the whitepaper's HTML has text before its first heading");
	$unnumbered = array('Abstract', 'Executive summary', 'Appendices');
	$sections = array();
	$n = 1;
	foreach ($parts as $part) {
		preg_match('#^<h1 id="([^"]+)"[^>]*>(.*?)</h1>\s*#s', $part, $m) or fail("a section without a heading");
		$title = html_entity_decode(strip_tags($m[2]), ENT_QUOTES);
		$sections[] = array('slug' => $m[1], 'number' => in_array($title, $unnumbered) ? null : ++$n,
			'title' => $title, 'html' => trim(substr($part, strlen($m[0]))));
	}

	// Each anchor's section, then every in-paper link (#id) as whitepaper/<section>#id.
	$home = array();
	foreach ($sections as $s) {
		$home[$s['slug']] = $s['slug'];
		preg_match_all('/ id="([^"]+)"/', $s['html'], $m);
		foreach ($m[1] as $id) $home[$id] = $s['slug'];
	}
	foreach ($sections as $i => $s) {
		$sections[$i]['html'] = preg_replace_callback('/href="#([^"]+)"/', function ($m) use ($home) {
			if (!isset($home[$m[1]])) fail("the whitepaper links to #{$m[1]}, which no section has");
			return 'href="whitepaper/' . $home[$m[1]] . ($home[$m[1]] === $m[1] ? '' : '#' . $m[1]) . '"';
		}, $s['html']);
	}
	return $meta + array('pdf' => $pdf, 'sections' => $sections);
}

// Every file under $dir, as relative path => content hash.
function snapshot ($dir)
{
	$files = array();
	if (!is_dir($dir)) return $files;
	$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
	foreach ($it as $f) $files[substr($f->getPathname(), strlen($dir) + 1)] = md5_file($f->getPathname());
	ksort($files);
	return $files;
}

// One line per file: + new, ~ changed, = unchanged, - removed; then the totals.
function report ($before, $after)
{
	$count = array('+' => 0, '~' => 0, '=' => 0, '-' => 0);
	$all = array_unique(array_merge(array_keys($before), array_keys($after)));
	sort($all);
	foreach ($all as $rel) {
		$mark = !isset($before[$rel]) ? '+' : (!isset($after[$rel]) ? '-' : ($before[$rel] === $after[$rel] ? '=' : '~'));
		$count[$mark]++;
		print("  $mark $rel\n");
	}
	say("{$count['+']} new, {$count['~']} changed, {$count['=']} unchanged, {$count['-']} removed");
}

function remove_tree ($path)
{
	if (!file_exists($path) && !is_link($path)) return;
	if (is_file($path) || is_link($path)) { unlink($path); return; }
	foreach (scandir($path) as $n) if ($n !== '.' && $n !== '..') remove_tree("$path/$n");
	rmdir($path);
}

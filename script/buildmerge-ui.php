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
 *   source.ts                      which commit of this repo the copy came from
 *
 * Run it whenever the site should see a spec change: then `npx ng serve` in backlog-ui shows it
 * at http://localhost:4200/spec. Nothing reaches backlog-web or the live site until
 * backlog-ui's own `php script/buildmerge-web.php`, which refuses a copy taken from
 * uncommitted changes here (marked "+dirty"). So: try drafts freely, commit before deploying.
 */

$specRoot = realpath(__DIR__ . '/..');
$ui = realpath($specRoot . '/../backlog-ui');
function say ($s)  { print("buildmerge-ui: $s\n"); }
function fail ($s) { fwrite(STDERR, "buildmerge-ui: FAILED — $s\n"); exit(1); }
if (!$ui) fail("no backlog-ui beside backlog-spec");

$git = 'git -C ' . escapeshellarg($specRoot);
$commit = trim((string)shell_exec("$git rev-parse --short HEAD 2>/dev/null")) ?: 'unknown';
$dirty = trim((string)shell_exec("$git status --porcelain -- spec schema examples 2>/dev/null")) !== '';
$source = $commit . ($dirty ? '+dirty' : '');

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

file_put_contents("$out/source.ts",
	"// Written by backlog-spec's script/buildmerge-ui.php: the backlog-spec commit this copy came\n" .
	"// from, with +dirty if it included uncommitted changes (buildmerge-web.php refuses those).\n" .
	"export const SPEC_COMMIT = '$source';\n");
file_put_contents("$out/README.md",
	"# src/content/backlog-spec/\n\n" .
	"Generated: do not edit. backlog-spec's `script/buildmerge-ui.php` replaces this folder with\n" .
	"the spec text and JSON Schema the site publishes, from backlog-spec $source.\n");
say("backlog-spec $source -> backlog-ui/src/content/backlog-spec/");
report($before, snapshot($out));
if ($dirty) say("note: includes uncommitted changes; fine for ng serve, refused by buildmerge-web.php");
say("next: npx ng serve in backlog-ui to see it; buildmerge-web.php there when ready to deploy");

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

// Pandoc template for the whitepaper PDF (script/build-pdf.sh): Pandoc fills the template variables from
// metadata.yml and the sections, and Typst typesets the result. Paths start at the repository
// root (typst compile --root .). Fonts are in whitepaper/pdf/fonts (--font-path).
//
// Order: cover, notice (license), contents, then the paper; pages are numbered from the abstract.

#let ink = rgb("#1f2328")
#let muted = rgb("#59636e")
#let border = rgb("#d0d7de")
#let subtle = rgb("#f6f8fa")
#let brand = rgb("#b45309")

#let doc-title = "$title$"
#let doc-version = "$version$"
#let doc-date = "$date$"
#let paper = "$if(paper)$$paper$$else$us-letter$endif$"

#let ymd = doc-date.split("-").map(int)
#set document(title: doc-title, author: "$author$", keywords: ("Open Backlog", "backlog", "git", "Markdown"),
  date: datetime(year: ymd.at(0), month: ymd.at(1), day: ymd.at(2)))
#set text(font: "IBM Plex Sans", size: 10pt, fill: ink, lang: "en", region: "US")
#set par(leading: 0.62em, spacing: 1.05em, justify: false)
#set list(indent: 0.4em, body-indent: 0.5em, spacing: 0.75em)
#set enum(indent: 0.4em, body-indent: 0.5em, spacing: 0.75em)
#set terms(hanging-indent: 1.5em)
#set footnote.entry(separator: line(length: 25%, stroke: 0.5pt + border))
#show footnote.entry: set text(size: 8.5pt)
#show link: set text(fill: brand)

// Code: Plex Mono on a pale panel. A block of up to 30 lines is kept on one page; a longer one
// (appendix B's schema) breaks between lines.
#show raw: set text(font: "IBM Plex Mono", size: 0.88em)
#show raw.where(block: true): it => block(
  width: 100%, fill: subtle, stroke: 0.5pt + border, radius: 3pt, inset: 8pt,
  breakable: it.lines.len() > 30, it,
)

// Tables: a rule under the header, hairlines between rows, the header repeated on a new page. A
// table of up to 12 rows is kept on one page.
#set table(
  stroke: (x, y) => if y == 0 { (bottom: 0.8pt + ink) } else { (bottom: 0.4pt + border) },
  inset: (x: 5pt, y: 4.5pt),
  align: left,
)
#show table: set text(size: 9pt)
#show table.cell.where(y: 0): set text(weight: "bold")
#show figure.where(kind: table): it => {
  // Pandoc writes #figure(align(center)[#table(...)], kind: table); typeset the table itself,
  // left-aligned, in a block that may break only if the table is long.
  let t = if it.body.func() == align { it.body.body } else { it.body }
  let cols = if type(t.columns) == int { t.columns } else { t.columns.len() }
  let cells = t.children.filter(c => c.func() != table.header and c.func() != table.hline)
  let rows = calc.ceil(cells.len() / calc.max(1, cols))
  set align(left)  // the figure would otherwise center the table and its cells
  block(breakable: rows > 12, width: 100%, above: 1.2em, below: 1.2em, t)
}

// Figures (the diagrams): kept whole, caption below in muted text.
#show figure.where(kind: image): set block(breakable: false, above: 1.4em, below: 1.4em)
#show figure.caption: set text(size: 8.5pt, fill: muted)

// Headings: each numbered section on a new page, its number in brand orange.
#set heading(numbering: "1.1")
#show heading: set text(weight: "bold")
#show heading.where(level: 1): it => {
  pagebreak(weak: true)
  v(1.2in)
  if it.numbering != none {
    text(font: "IBM Plex Mono", size: 11pt, fill: brand, weight: "bold")[SECTION #counter(heading).display("1")]
    v(0.3em)
  }
  text(size: 22pt, it.body)
  v(0.2em)
  line(length: 36pt, stroke: 2.5pt + brand)
  v(0.9em)
}
#show heading.where(level: 2): set text(size: 13pt)
#show heading.where(level: 2): set block(above: 1.6em, below: 0.8em)
#show heading.where(level: 3): set text(size: 11pt)

// Page: running header (the paper's title and the current section) and page number, from the
// abstract on. No header on a page where a section starts.
#let body-started = state("body-started", false)
#set page(
  paper: paper,
  margin: (x: 1in, top: 1in, bottom: 0.9in),
  header: context {
    if not body-started.get() { return }
    let pg = here().page()
    if query(heading.where(level: 1)).any(h => h.location().page() == pg) { return }
    let before = query(heading.where(level: 1).before(here()))
    if before.len() == 0 { return }
    let h = before.last()
    let num = if h.numbering != none { numbering(h.numbering, ..counter(heading).at(h.location())) + "  " } else { "" }
    set text(size: 8.5pt, fill: muted)
    grid(columns: (1fr, auto), doc-title, num + h.body)
    v(-0.5em)
    line(length: 100%, stroke: 0.4pt + border)
  },
  footer: context {
    if not body-started.get() { return }
    set text(size: 8.5pt, fill: muted)
    align(center, counter(page).display("1"))
  },
)

// Cover: the brand's cover for this paper size, with the version and date filled in.
#let cover-file = if paper == "a4" { "/whitepaper/pdf/cover-a4.svg" } else { "/whitepaper/pdf/cover-letter.svg" }
#page(margin: 0pt, header: none, footer: none,
  image(bytes(read(cover-file).replace("{{version}}", doc-version).replace("{{date}}", doc-date)),
    format: "svg", width: 100%))

// Notice (back of the cover).
#page[
  #set text(size: 9pt, fill: muted)
  #v(1fr)
  #text(fill: ink, weight: "bold")[$title$: $subtitle$] \
  Version #doc-version, #doc-date

  © 2026 $author$. This work is licensed under the Creative Commons Attribution 4.0
  International License (CC BY 4.0): #link("https://creativecommons.org/licenses/by/4.0/"). You
  may share and adapt it for any purpose if you give credit.

  The normative rules are in the Open Backlog specification,
  #link("https://openbacklog.org/spec")[openbacklog.org/spec]. Where this paper and the
  specification differ, the specification wins.
]

// Contents.
#page[
  #show outline.entry.where(level: 1): set block(above: 1.1em)
  #show outline.entry.where(level: 1): set text(weight: "bold")
  #text(size: 22pt, weight: "bold")[Contents]
  #v(0.6em)
  #outline(title: none, depth: 2, indent: 1.5em)
]

#body-started.update(true)
#counter(page).update(1)

$body$

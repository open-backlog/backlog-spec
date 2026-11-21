# whitepaper/

The Open Backlog whitepaper: why a backlog belongs beside its code, the convention and its
reasoning, and the tools built for it. One Markdown file per section, in order:

| File | Section |
|---|---|
| `01-abstract.md` | Abstract and executive summary |
| `02-the-problem.md` … `17-roadmap-and-conclusion.md` | Sections 2 to 17 |
| `18-appendices.md` | Appendices |

`metadata.yml` holds the title, subtitle, author, version, date and license; `references.bib`
holds every source the paper cites, as `[@key]`; they are listed in appendix D. Every section
is written (2.1.1–2.1.8). Appendix B copies the JSON Schema in full, and `npm test` fails if
that copy ever differs from `schema/0.1/`.

`drafts/` keeps earlier versions of the paper with a decision log; nothing there is published.

`figures/` holds the diagrams the paper embeds, copied from backlog-art's `diagrams/` by
`script/sync-art.sh`; change them there, never here.

## The PDF

```sh
brew install pandoc typst      # once
script/build-pdf.sh            # both sizes
script/build-pdf.sh letter     # dist/open-backlog-whitepaper-<version>.pdf (US Letter)
script/build-pdf.sh a4         # dist/open-backlog-whitepaper-<version>-a4.pdf
```

Pandoc turns the numbered sections into Typst, with citations from
`references.bib`, and Typst typesets them: cover, license notice, contents, then the paper, with
running headers, page numbers and references. Two builds of the same files give the same PDF.
The build reads only this folder: the numbered sections, `metadata.yml`, `references.bib` and
`pdf/`. It never reads `drafts/` and never fetches anything.

| `pdf/` | |
|---|---|
| `template.typ` | the page design: fonts, colors, headings, tables, code, cover, contents |
| `whitepaper.lua` | numbers sections to match the files (`06-…` is section 6), uses the print diagrams, lets tables size their columns |
| `cover-letter.svg`, `cover-a4.svg` | the cover; the build fills in `version` and `date` from `metadata.yml` |
| `figures/` | the diagrams in light colors only (Typst ignores the CSS variables in `figures/`) |
| `fonts/` | IBM Plex Sans and Mono (OFL) |

Everything in `pdf/` except the template and the filter comes from backlog-art through
`script/sync-art.sh`. To read the paper without building it, any Markdown viewer works, file by file.

The normative rules are in [spec/](../spec/); where the paper and the spec differ, the spec wins.
Licensed [CC-BY-4.0](../LICENSE).

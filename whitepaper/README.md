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

The paper is rendered to the site and built to a PDF with Pandoc and Typst; the build arrives in
its own change. To read it today, any Markdown viewer works, file by file.

The normative rules are in [spec/](../spec/); where the paper and the spec differ, the spec wins.
Licensed [CC-BY-4.0](../LICENSE).

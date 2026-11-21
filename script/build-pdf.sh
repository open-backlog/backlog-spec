#!/bin/sh
# Build the whitepaper PDF. Setup, once: brew install pandoc typst
#
#   script/build-pdf.sh           both sizes
#   script/build-pdf.sh letter    US Letter: dist/open-backlog-whitepaper-<version>.pdf
#   script/build-pdf.sh a4        A4:        dist/open-backlog-whitepaper-<version>-a4.pdf
#
# Pandoc turns the numbered sections into Typst (citations from references.bib), and Typst
# typesets them with whitepaper/pdf/ (template, filter, cover, print diagrams, fonts). Reads only
# whitepaper/: never drafts/, and nothing from the network. The same files give the same PDF.
# buildmerge-ui (2.1.13) calls this to put the PDF on the site; CI (2.1.12) calls it on release.
set -e
cd "$(dirname "$0")/.."

for tool in pandoc typst; do
  command -v $tool >/dev/null || { echo "build-pdf: $tool not found (brew install pandoc typst)" >&2; exit 1; }
done

case "${1:-all}" in
  all) sizes="letter a4" ;;
  letter|a4) sizes="$1" ;;
  *) echo "usage: script/build-pdf.sh [letter|a4]" >&2; exit 2 ;;
esac

version=$(sed -n 's/^version: *"*\([^"]*\)"*$/\1/p' whitepaper/metadata.yml)
[ -n "$version" ] || { echo "build-pdf: no version in whitepaper/metadata.yml" >&2; exit 1; }
mkdir -p dist

for size in $sizes; do
  if [ "$size" = a4 ]; then paper=a4; suffix=-a4; else paper=us-letter; suffix=; fi
  typ="dist/whitepaper$suffix.typ"
  pdf="dist/open-backlog-whitepaper-$version$suffix.pdf"
  pandoc whitepaper/[0-9][0-9]-*.md --metadata-file=whitepaper/metadata.yml \
    --resource-path=whitepaper --citeproc --fail-if-warnings \
    --lua-filter=whitepaper/pdf/whitepaper.lua --template=whitepaper/pdf/template.typ \
    -V paper=$paper -t typst -o "$typ"
  typst compile --root . --ignore-system-fonts --font-path whitepaper/pdf/fonts "$typ" "$pdf"
  echo "build-pdf: wrote $pdf"
done

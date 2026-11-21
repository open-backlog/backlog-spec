#!/bin/zsh
# Copy what the whitepaper needs from backlog-art (the sibling repo). Run it after backlog-art
# changes, then commit. Copies, not links, so the PDF build never depends on a sibling checkout
# or the website.
#   whitepaper/figures/       the diagrams, light and dark (the Markdown and the site)
#   whitepaper/pdf/figures/   the same diagrams in light colors only (the PDF)
#   whitepaper/pdf/           the cover, US Letter and A4
#   whitepaper/pdf/fonts/     IBM Plex Sans (static) and Mono, and their license
set -e
ART="$(cd "$(dirname "$0")/../../backlog-art" && pwd)"
cd "$(dirname "$0")/.."
mkdir -p whitepaper/figures whitepaper/pdf/figures whitepaper/pdf/fonts
for d in folder-tree story-lifecycle tiers; do
  cp "$ART/diagrams/$d.svg" whitepaper/figures/
  cp "$ART/diagrams/print/$d.svg" whitepaper/pdf/figures/
done
cp "$ART/cover/cover-letter.svg" "$ART/cover/cover-a4.svg" whitepaper/pdf/
for f in Regular Italic Bold BoldItalic; do
  cp "$ART/fonts/static/IBMPlexSans-$f.ttf" whitepaper/pdf/fonts/
done
for f in Regular Italic Bold; do
  cp "$ART/fonts/IBMPlexMono-$f.ttf" whitepaper/pdf/fonts/
done
cp "$ART/fonts/OFL.txt" whitepaper/pdf/fonts/
echo "synced from $ART"

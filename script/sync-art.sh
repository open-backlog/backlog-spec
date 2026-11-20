#!/bin/zsh
# Copy the core diagrams from backlog-art (the sibling repo) into whitepaper/figures/, where the
# whitepaper's Markdown embeds them. Run it after backlog-art changes, then commit. A copy, not a
# link, so the PDF build (2.1.11) never depends on a sibling checkout or the website.
ART="$(cd "$(dirname "$0")/../../backlog-art" && pwd)" || exit 1
cd "$(dirname "$0")/.." || exit 1
mkdir -p whitepaper/figures
cp "$ART"/diagrams/{folder-tree,story-lifecycle,tiers}.svg whitepaper/figures/
echo "synced from $ART"

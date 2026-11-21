-- Pandoc filter for the whitepaper PDF (script/build-pdf.sh). It leaves the Markdown untouched.
--
-- Section numbers: the paper calls its files "section N" (06-the-format.md is section 6), and
-- 01-abstract.md is section 1. So "Abstract", "Executive summary" and "Appendices" (and everything
-- under the appendices, which carry their own letters) are unnumbered, and the first numbered
-- section starts at 2.
--
-- Figures: figures/x.svg (light and dark, for screens) becomes pdf/figures/x.svg (light colors
-- only; Typst ignores CSS variables), as a path from the repository root.
--
-- Tables: Pandoc gives wide Markdown tables fixed relative column widths; the PDF lets Typst size
-- the columns to their content instead.

local UNNUMBERED = { ["Abstract"] = true, ["Executive summary"] = true, ["Appendices"] = true }
local in_appendices = false
local started = false

function Header(h)
  local title = pandoc.utils.stringify(h.content)
  if h.level == 1 and title == "Appendices" then in_appendices = true end
  if in_appendices or (h.level == 1 and UNNUMBERED[title]) then
    h.classes:insert("unnumbered")
    return h
  end
  if h.level == 1 and not started then
    started = true
    return { pandoc.RawBlock("typst", "#counter(heading).update(1)"), h }
  end
  return h
end

function Image(img)
  img.src = img.src:gsub("^figures/", "/whitepaper/pdf/figures/")
  return img
end

function Table(t)
  for i, spec in ipairs(t.colspecs) do
    t.colspecs[i] = { spec[1], pandoc.ColWidthDefault }
  end
  return t
end

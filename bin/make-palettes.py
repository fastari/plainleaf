#!/usr/bin/env python3
"""Build the color palettes in plainleaf/styles/colors/ from a few anchor colors.

Every palette fills the same 12 slots the templates use (base, surface, contrast,
muted, line, brand, brand-deep, brand-ink, on-brand, accent, accent-hover,
on-accent). You give the anchors; the script nudges shades darker or lighter
until every text/background pair the theme uses passes WCAG AA, then writes
one JSON file per palette. bin/check-contrast.py checks the result again.

The five shipped palettes start from color combinations published by Canva's
design school (names kept for credit). To add your own, add an entry to
PALETTES and run:  python3 bin/make-palettes.py

Usage: python3 bin/make-palettes.py [--print]
"""
import colorsys
import json
import pathlib
import sys

ROOT = pathlib.Path(__file__).resolve().parent.parent
OUT = ROOT / "plainleaf" / "styles" / "colors"

NAMES = {
    "base": "Background",
    "surface": "Surface (boxes, cards)",
    "contrast": "Text",
    "muted": "Muted text",
    "line": "Divider line",
    "brand": "Brand (hero band, badges)",
    "brand-deep": "Brand deep (large text, lines)",
    "brand-ink": "Brand ink (small brand text)",
    "on-brand": "Text on brand",
    "accent": "Accent (buttons)",
    "accent-hover": "Accent hover",
    "on-accent": "Text on accent",
}

# (foreground, background, ratio, which side moves when it fails)
RULES = [
    ("contrast", "base", 4.5, "fg"),
    ("contrast", "surface", 4.5, "fg"),
    ("muted", "base", 4.5, "fg"),
    ("muted", "surface", 4.5, "fg"),
    ("brand-deep", "base", 3.0, "fg"),
    ("brand-ink", "base", 4.5, "fg"),
    ("brand-ink", "surface", 4.5, "fg"),
    ("on-brand", "brand", 4.5, "bg"),
    ("on-accent", "accent", 4.5, "bg"),
    ("on-accent", "accent-hover", 4.5, "bg"),
]

# slug: (title, credit, anchors)
PALETTES = {
    "nautical": (
        "Nautical",
        "Canva: Timeless and nautical (#00293C #1E656D #F1F3CE #F62A00)",
        {
            "base": "#FCFCF5", "surface": "#F1F3CE", "contrast": "#00293C", "muted": "#3B5763",
            "line": "#DDE0C4", "brand": "#1E656D", "brand-deep": "#1E656D", "brand-ink": "#1E656D",
            "on-brand": "#FFFFFF", "accent": "#F62A00", "accent-hover": "#C92200", "on-accent": "#FFFFFF",
        },
    ),
    "sand": (
        "Sand",
        "Canva: Polished and inviting (#EAE2D6 #D5C3AA #867666 #E1B80D)",
        {
            "base": "#FBF8F3", "surface": "#EAE2D6", "contrast": "#2B2520", "muted": "#5F5348",
            "line": "#D5C3AA", "brand": "#D5C3AA", "brand-deep": "#867666", "brand-ink": "#867666",
            "on-brand": "#2B2520", "accent": "#E1B80D", "accent-hover": "#EBC93F", "on-accent": "#2B2520",
        },
    ),
    "plum": (
        "Plum",
        "Canva: Stormy hues (#494E6B #98878F #985E6D #192231)",
        {
            "base": "#FAF8F9", "surface": "#EFE9EC", "contrast": "#192231", "muted": "#494E6B",
            "line": "#DDD3D8", "brand": "#494E6B", "brand-deep": "#985E6D", "brand-ink": "#985E6D",
            "on-brand": "#FFFFFF", "accent": "#985E6D", "accent-hover": "#7D4B58", "on-accent": "#FFFFFF",
        },
    ),
    "harbor": (
        "Harbor",
        "Canva: Grecian holiday (#2988BC #2F496E #F4EADE #ED8C72)",
        {
            "base": "#FDFBF7", "surface": "#F4EADE", "contrast": "#1B2A3F", "muted": "#3F5068",
            "line": "#E3D7C8", "brand": "#2988BC", "brand-deep": "#2988BC", "brand-ink": "#2F496E",
            "on-brand": "#FFFFFF", "accent": "#ED8C72", "accent-hover": "#F2A792", "on-accent": "#1B2A3F",
        },
    ),
    "stone": (
        "Stone",
        "Canva: Muted tones (#C0B283 #DCD0C0 #F4F4F4 #373737)",
        {
            "base": "#F4F4F4", "surface": "#FFFFFF", "contrast": "#373737", "muted": "#5A5A5A",
            "line": "#DCD0C0", "brand": "#DCD0C0", "brand-deep": "#8A7D55", "brand-ink": "#6B6040",
            "on-brand": "#373737", "accent": "#373737", "accent-hover": "#4D4D4D", "on-accent": "#F4F4F4",
        },
    ),
}


def luminance(hex_colour):
    hex_colour = hex_colour.lstrip("#")
    channels = [int(hex_colour[i : i + 2], 16) / 255 for i in (0, 2, 4)]
    linear = [c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4 for c in channels]
    return 0.2126 * linear[0] + 0.7152 * linear[1] + 0.0722 * linear[2]


def ratio(a, b):
    la, lb = sorted((luminance(a), luminance(b)), reverse=True)
    return (la + 0.05) / (lb + 0.05)


def shift(hex_colour, away_from):
    """Move a colour one small step in lightness, away from another colour."""
    r, g, b = (int(hex_colour.lstrip("#")[i : i + 2], 16) / 255 for i in (0, 2, 4))
    h, l, s = colorsys.rgb_to_hls(r, g, b)
    step = -0.01 if luminance(hex_colour) <= luminance(away_from) else 0.01
    l = min(1.0, max(0.0, l + step))
    r, g, b = colorsys.hls_to_rgb(h, l, s)
    return "#{:02X}{:02X}{:02X}".format(round(r * 255), round(g * 255), round(b * 255))


def solve(colours):
    colours = {k: v.upper() for k, v in colours.items()}
    for _ in range(200):
        changed = False
        for fg, bg, need, moves in RULES:
            if ratio(colours[fg], colours[bg]) >= need:
                continue
            if moves == "fg":
                colours[fg] = shift(colours[fg], colours[bg])
            else:
                colours[bg] = shift(colours[bg], colours[fg])
            changed = True
        if not changed:
            return colours
    raise SystemExit("Could not make the palette pass: " + json.dumps(colours))


def main():
    OUT.mkdir(parents=True, exist_ok=True)
    for index, (slug, (title, credit, anchors)) in enumerate(PALETTES.items(), start=1):
        colours = solve(anchors)
        data = {
            "$schema": "https://schemas.wp.org/trunk/theme.json",
            "version": 3,
            "title": title,
            "description": credit,
            "settings": {
                "color": {
                    "palette": [{"slug": k, "name": NAMES[k], "color": colours[k]} for k in NAMES]
                }
            },
        }
        path = OUT / f"{index:02d}-{slug}.json"
        path.write_text(json.dumps(data, indent="\t") + "\n")
        changed = {k: (anchors[k].upper(), v) for k, v in colours.items() if anchors[k].upper() != v}
        print(f"{path.relative_to(ROOT)}  adjusted: {changed or 'none'}")


if __name__ == "__main__":
    sys.exit(main())

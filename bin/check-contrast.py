#!/usr/bin/env python3
"""Check WCAG contrast for the colour pairs the theme actually uses.

Reads the palette from theme.json and every style variation in styles/,
then reports each pair with its ratio and the WCAG level it reaches.

Usage: python3 bin/check-contrast.py
Exit code is 1 if any pair falls below the level it needs.
"""
import json
import pathlib
import sys

THEME = pathlib.Path(__file__).resolve().parent.parent / "plainleaf"

# (foreground slug, background slug, required ratio, where it is used)
PAIRS = [
    ("contrast", "base", 4.5, "body text"),
    ("contrast", "surface", 4.5, "text on surface boxes"),
    ("muted", "base", 4.5, "dates, meta, captions"),
    ("muted", "surface", 4.5, "meta on surface boxes"),
    ("on-brand", "brand", 4.5, "text on the brand hero band and topic badges"),
    ("on-accent", "accent", 4.5, "text on accent buttons"),
    ("on-accent", "accent-hover", 4.5, "text on hovered accent buttons"),
    ("brand-deep", "base", 3.0, "large text (24px+) and UI lines only"),
    ("brand-ink", "base", 4.5, "small brand text: topic numbers, labels"),
    ("brand-ink", "surface", 4.5, "small brand text on surface boxes"),
]


def luminance(hex_colour):
    hex_colour = hex_colour.lstrip("#")
    channels = [int(hex_colour[i : i + 2], 16) / 255 for i in (0, 2, 4)]
    linear = [c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4 for c in channels]
    return 0.2126 * linear[0] + 0.7152 * linear[1] + 0.0722 * linear[2]


def ratio(a, b):
    la, lb = sorted((luminance(a), luminance(b)), reverse=True)
    return (la + 0.05) / (lb + 0.05)


def palette(path):
    data = json.loads(path.read_text())
    entries = data.get("settings", {}).get("color", {}).get("palette", [])
    return {p["slug"]: p["color"] for p in entries}


def main():
    failed = False
    sources = [("Default (light)", THEME / "theme.json")]
    sources += [(p.stem, p) for p in sorted((THEME / "styles").rglob("*.json"))]
    for label, path in sources:
        colours = palette(path)
        if not colours:
            continue
        print(f"\n{label}")
        for fg, bg, need, use in PAIRS:
            if fg not in colours or bg not in colours:
                continue
            r = ratio(colours[fg], colours[bg])
            level = "AAA" if r >= 7 else "AA" if r >= 4.5 else "AA large" if r >= 3 else "fail"
            ok = r >= need
            failed |= not ok
            mark = "ok  " if ok else "FAIL"
            print(f"  {mark} {r:5.2f}:1 {level:8} {fg} on {bg} ({colours[fg]} on {colours[bg]}), {use}")
    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())

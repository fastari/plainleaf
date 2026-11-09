---
name: rebrand
description: Change, save or restore a Plainleaf site's look through WordPress settings (never theme files) - colors, fonts, logo, photo, hero image, wording and tone, optionally inspired by a reference website - and export or apply a brand preset file to rebuild a site's look on a fresh install. Use when the user wants to rebrand, restyle, change colors or fonts, match another site's look, save their branding to a file, or restore or re-apply their branding.
---

# Rebrand, save or restore a Plainleaf site's look

Branding lives in WordPress, never in the theme files, so theme updates can't overwrite it.
You change it through the WordPress MCP (AI Engine): `wp_get_site_style`,
`wp_update_site_style`, `wp_get_option`, `wp_update_option`, `wp_upload_media`.

Never edit `plainleaf/theme.json`, `style.css` or templates for one site's branding. If
the user asks for that, explain why it would be overwritten on the next update and do it
in WordPress instead.

## Before you change anything

1. Call `wp_get_site_info` and confirm the site and that the active theme is Plainleaf.
2. Save the current state so it can be undone: `wp_get_site_style` (all of it) and the
   options you are about to change. Offer to write it as a brand preset (see Export).
3. Tell the user what you will change and wait for a yes. Every change can also be undone
   with `mcp_list_changes` / `mcp_undo_change`.

## Colors

The theme uses 12 color slots: `base`, `surface`, `contrast`, `muted`, `line`, `brand`,
`brand-deep`, `brand-ink`, `on-brand`, `accent`, `accent-hover`, `on-accent`.
Templates only use these slugs, so changing the slot values recolors the whole site.

- Ready-made palettes: `plainleaf/styles/colors/*.json` (Nautical, Sand, Plum, Harbor,
  Stone, Night). Offer these first.
- Custom palette: pick 4-5 anchor colors, fill all 12 slots, then check contrast. The
  pairs and ratios are in `bin/check-contrast.py`; the derivation approach is in
  `bin/make-palettes.py` (add the palette there and run it to auto-fix contrast). Never
  apply a palette that fails a pair; say which pair failed and how you fixed it.
- Apply: `wp_update_site_style` with
  `{"global_styles": {"settings": {"color": {"palette": {"theme": [ ...12 entries... ]}}}}}`.

## Fonts

Five pairings ship in `plainleaf/styles/typography/` (plain, editorial, warm, modern,
clean). Each sets three roles: `display` (titles), `serif` (reading text), `sans`
(interface), plus `mono`. Apply by copying the file's `settings.typography.fontFamilies`
into `{"global_styles": {"settings": {"typography": {"fontFamilies": {"theme": [...]}}}}}`.

For a font that doesn't ship with the theme, the user installs it in Appearance > Editor >
Styles > Typography > Manage fonts (Font Library, stored in uploads, safe from updates).
Then set the role in global styles to that family. Prefer open-licensed fonts (OFL).

## Logo, icon and photo

Upload with `wp_upload_media`, then set the option to the new attachment ID:
`plainleaf_logo` (header/footer), `plainleaf_logo_phone` (optional compact logo),
`site_icon` (browser/phone icon, square, at least 512px), `plainleaf_author_photo`.
`plainleaf_logo_mode`: `auto` (logo, or title until a logo is set), `both`, `title`.
WordPress rejects SVG uploads by default: convert to PNG at 2x display size.

Home hero: `plainleaf_hero_media` (an image, or a short muted MP4/WebM) and
`plainleaf_hero_layout` (`right` or `full`). For an illustration or animation in code, use
Additional CSS on `.pl-hero-art__layer > span:nth-child(1..3)` (keep motion under
`prefers-reduced-motion: no-preference`).

## Wording and tone

Every visible sentence is an option `plainleaf_text_<key>` (list and defaults:
`plainleaf_text_sections()` in `plainleaf/inc/site-text.php`). Rewrite in the user's voice:
short, plain, specific. Show the old and new text side by side and get approval before
writing. Empty values hide optional text.

## Inspired by a reference website

1. Read the site (WebFetch). Note its background, text, brand and accent colors, its
   heading and body fonts, and its tone. Don't copy logos, images, copy or trademarks:
   take the feeling, not the brand.
2. Propose a 12-slot palette (contrast-checked), the closest shipped font pairing or an
   OFL alternative, and 2-3 sample lines of wording. Wait for approval, then apply.

## Restore from a brand preset

Use this to put a saved look back on a fresh install, or to copy a brand to another site.
The format is in `docs/brand-preset.md`. Never delete content.

1. **Check first.** `wp_get_site_info`: confirm the site URL with the user, and that
   Plainleaf is installed **and active**. Plainleaf registers its settings only while it
   is the active theme (the REST API ignores `plainleaf_*` settings before that), and
   Global Styles belong to the active theme. Uploading media beforehand is fine. On a
   live site: upload the zip, activate, then restore right away; visitors see the
   neutral look for about a minute.
2. Read the preset and list what it will change: site title, palette, font pairing, CSS,
   each option, each media file, each content fix. Flag anything that would overwrite a
   different value already on the site. Wait for a yes.
3. Save the current state (`wp_get_site_style`, the options you'll touch). Changes are
   also undoable with `mcp_undo_change`.
4. **Apply, in this order:**
   1. Site: `blogname` and `blogdescription`.
   2. Fonts: if `font_pairing` is set, read `plainleaf/styles/typography/NN-<slug>.json`
      and merge its `settings.typography.fontFamilies` as `fontFamilies.theme`.
   3. Global styles: `wp_update_site_style` with `global_styles` (merge, don't replace).
   4. CSS: read the current Additional CSS first. If it's empty, set `css`; if not, show
      both and ask whether to replace or append.
   5. Media: for each entry, look for a file with the same name in the Media Library
      (`wp_get_media`) and reuse it, otherwise upload it with `wp_upload_media` (base64
      for local files, each under a few MB, alt text from the preset). Then set the
      option to the attachment ID (`site_icon`, `plainleaf_logo`, `plainleaf_logo_phone`,
      `plainleaf_author_photo`, `plainleaf_hero_media`).
   6. Options: `wp_update_option` for each `options` entry (booleans as true/false).
   7. Content fixes: for each fix, search posts, pages and synced patterns
      (`wp_get_posts` with `search`, `post_status: any`), list the matches and wait for a
      yes, then replace only the exact `find` text in each. Never trash or delete posts.
5. **Check the result:** open the home page, a post, the blog, a topic page and About
   (browser or `wp_get_rendered`) and compare with the old site or screenshots. Report
   what was applied, anything skipped and why, and anything left to do by hand (for
   example a font that isn't bundled: install it in the Font Library).

## Export a brand preset

Write `brand-preset.json` (format: `docs/brand-preset.md`) from `wp_get_site_style`
(global styles and CSS), `blogname`, `blogdescription`, and every `plainleaf_*` option
that differs from the default. For media, download the logo, phone logo, site icon and
author photo files next to the JSON (in `files/`) and reference them by path. Tell the
user to keep the preset private (a private repo, not the theme repo). Never include
passwords, API keys or tokens.

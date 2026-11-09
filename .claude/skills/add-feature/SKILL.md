---
name: add-feature
description: Add a section, block pattern, template, template part or small PHP feature to the Plainleaf theme safely, following its conventions (tokens only, editable text, escaping, no build step). Use when the user wants a new section, layout, page template, block or theme option in Plainleaf itself.
---

# Add a feature to Plainleaf

First decide where it belongs:

- **Only for one site** (a sales page, a course landing page, one site's special layout):
  don't add it to the theme. Build it as a page or a synced pattern in WordPress (MCP
  `wp_create_post`, `wp_write_blocks`), so it lives in that site's database and
  survives updates. Plugin features (courses, shops, memberships) come from plugins; they
  pick up the theme's colors and fonts automatically.
- **Useful to any Plainleaf site:** add it to the theme, as below.

Read `CONTRIBUTING.md` and look at a similar existing file before writing.

## Conventions (must follow)

- **Tokens only.** Colors: `var:preset|color|<slot>` in block markup,
  `var(--wp--preset--color--<slot>)` in CSS, using the 12 slots. Fonts: the `display`,
  `serif`, `sans`, `mono` roles. Spacing: `var:preset|spacing|10..80`. Never a hex value,
  font name or pixel color outside `theme.json` and `styles/`.
- **No hardcoded visible text** in templates or patterns used by templates. Add a field
  to `plainleaf_text_sections()` in `inc/site-text.php` (label, default, type, required,
  help) and print it with `plainleaf_text()` / `plainleaf_text_lines()` /
  `plainleaf_text_attr()`. Inserter-only patterns may contain neutral starter text.
- **Prefix everything** `plainleaf_` (PHP functions, options, meta, hooks), `pl-` (CSS
  classes), `plainleaf/` (blocks, patterns). Text domain `plainleaf`.
- **Escape late, sanitize early:** `esc_html`, `esc_attr`, `esc_url`, `wp_kses` on output;
  `sanitize_*`/`absint` on input; `register_setting` with a `sanitize_callback`;
  `current_user_can()` on admin pages; nonces on any custom form handler.
- **No build step.** Blocks are plain ES5 using `window.wp.*` with a `render.php`.
- **No external requests** on the front end (no CDNs, no Google Fonts, no trackers).
- **Accessible:** visible focus, labels on controls, AA contrast, respect
  `prefers-reduced-motion`.

## Where things go

| Kind | Path |
| --- | --- |
| Pattern | `plainleaf/patterns/<name>.php` with the header block (Title, Slug `plainleaf/<name>`, Categories, Description) |
| Template | `plainleaf/templates/<name>.html` (and `customTemplates` in theme.json if user-selectable) |
| Template part | `plainleaf/parts/<name>.html` (register area in theme.json `templateParts`) |
| Block | `plainleaf/blocks/<name>/` (`block.json`, `index.js`, `render.php`), registered in `inc/blocks.php` |
| Setting | `inc/settings.php` (register + field) |
| CSS | `plainleaf/style.css` (only what theme.json can't express) |

## Finish

1. `make build` (PHP lint, JSON, contrast, zip) and `vendor/bin/phpcs` must pass.
2. Test locally with `make dev` (http://127.0.0.1:9400) in light and Night palettes and at
   phone width. Turn the feature's text off/empty and check nothing breaks.
3. Open a PR; don't push to main, and don't change the version. Give the PR a title that
   reads well in release notes, and label it `release:minor` for a new feature (see the
   `release` skill: merging releases automatically).

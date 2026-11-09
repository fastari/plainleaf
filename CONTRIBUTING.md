# Contributing

I maintain Plainleaf for my own sites, so pull requests and issues are open to
collaborators only. If you want changes, fork it: the GPL allows it, and the
[Name](README.md#name) section asks only that you give your copy a different name.
Security problems are the exception: please report them privately (see
[SECURITY.md](SECURITY.md)).

The rest of this page is how changes are made here, and is useful if you keep a fork.

## Good pull requests

- **Small and focused:** one fix or feature per PR.
- **Generic:** useful to any Plainleaf site. Site-specific layouts belong in that site's
  WordPress (pages, synced patterns, Additional CSS), not in the theme.
- **Explained:** what changed, why, and how you tested it (screenshots for visual changes,
  in the default and Night palettes and at phone width).
- **Passing checks:** `make build` and `make lint` locally; the Security workflow on the PR.

## Theme rules

1. **Tokens only.** Colors use the 12 palette slots, fonts use the `display`, `serif`,
   `sans` and `mono` roles, and spacing uses the spacing presets. No hex values, font names
   or brand assets outside `theme.json` and `styles/`.
2. **No hardcoded visible text** in templates or the patterns they use. Add a field to
   `plainleaf_text_sections()` in `plainleaf/inc/site-text.php` and print it with
   `plainleaf_text()`.
3. **Prefix everything:** `plainleaf_` for PHP, options, meta and hooks; `pl-` for CSS
   classes; `plainleaf/` for blocks and patterns. Text domain `plainleaf`.
4. **Security:** escape on output, sanitize on input, check capabilities, use nonces for
   custom forms, `wp_safe_redirect` only. No external requests on the front end.
5. **No build step.** Blocks are plain ES5 with a `render.php`.
6. **Accessibility:** WCAG AA contrast (checked by `bin/check-contrast.py`), visible focus,
   labelled controls, motion only under `prefers-reduced-motion: no-preference`.
7. **Licensing:** everything must be GPL-compatible. Fonts must be OFL with their licence
   file included and credited in `plainleaf/readme.txt`.

Don't bump the version in a PR. Merging releases automatically; the maintainer adds the
release label (`release:patch`, `release:minor`, `release:major` or `release:skip`).
Only collaborators can open pull requests; only the maintainer merges.

## Using Claude Code

The `add-feature` skill walks through these rules. Ask it to "add a feature" and review
its PR like any other.

## Security issues

Don't open a public issue. See [SECURITY.md](SECURITY.md).

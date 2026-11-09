# Changelog

All notable changes to Plainleaf. Versions follow [semantic versioning](https://semver.org).

## [1.0.0] - 2026-11-08

First public release.

- Neutral default style, five color palettes (Nautical, Sand, Plum, Harbor, Stone) and a
  dark palette (Night), all WCAG AA.
- Five font pairings (Plain, Editorial, Warm, Modern, Clean), bundled, no external requests.
- Logo, compact phone logo, author photo, newsletter, social links, footer credit and RSS
  switch in Appearance > Theme settings; all visible text in Appearance > Site text.
- Home hero image or video (right half or whole band, with a pause button and reduced-motion
  support) in Theme settings, plus an empty decorative layer you can style with Additional CSS.
- About page starter pattern; pages open inside their template in the editor.
- Newsletter signup for any tool, listen and share buttons, topic pages and filter,
  related posts, Start here, `/llms.txt`.
- Automatic updates from public GitHub releases, no token.
- Claude Code skills: rebrand, add-feature, publish-from-notion, release,
  theme-security-review.
- PHPCS (WordPress security sniffs), Semgrep and a brand guard on every pull request and
  weekly; Dependabot for the tooling; CI actions pinned to exact commits.

### Security

- `/llms.txt` is sent with `X-Content-Type-Options: nosniff`.

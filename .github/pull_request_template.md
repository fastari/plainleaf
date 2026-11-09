## What this changes

<!-- One or two sentences, written for site owners. The PR title becomes a line in the release notes. -->

## Why

<!-- The problem it solves, or link the issue: "Fixes #123". -->

## Release type

<!-- Theme changes (anything in plainleaf/) need exactly one release label before merging.
     Contributors: tick the one you think fits; the maintainer adds the label. -->

- [ ] `release:patch`: a fix or small tweak (1.2.3 → 1.2.4)
- [ ] `release:minor`: a new feature or setting; existing sites look the same (1.2.3 → 1.3.0)
- [ ] `release:major`: changes how existing sites look, or removes or renames a setting (1.2.3 → 2.0.0)
- [ ] `release:skip`: theme files changed, but site owners won't notice
- [ ] No theme files changed (docs, CI or tooling only): no label needed

## How I tested it

- [ ] `make build` passes (versions, PHP syntax, JSON, contrast)
- [ ] `make lint` passes (PHPCS with WordPress security sniffs)
- [ ] Checked locally with `make dev` in the default and Night palettes, and at phone width
- [ ] Screenshots below for anything visual

## Checklist

- [ ] Uses the theme's tokens only (12 color slots, font roles, spacing presets), no hard-coded colors or fonts
- [ ] Any text visitors see is editable in Appearance > Site text, not hard-coded in a template
- [ ] Output is escaped, input is sanitized, admin actions check permissions
- [ ] No personal data, secrets, API keys or details of a private site
- [ ] Doesn't change the version number (releases set it)

## Screenshots

<!-- Before and after, if anything looks different. -->

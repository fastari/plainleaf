---
name: release
description: Release a new version of the Plainleaf theme - bump the version in every place, write the CHANGELOG entry, build and check the zip, and publish a GitHub release that sites auto-update from. Use when the user wants to release, ship, publish or version the theme.
---

# Release Plainleaf

Sites update from public GitHub releases (`plainleaf/inc/updater.php`). A release is
outward-facing: every site with auto-updates installs it within about 12 hours. Confirm
with the user before publishing.

## 1. Pick the version

Semantic versioning: patch (1.0.1) for fixes, minor (1.1.0) for new features that don't
change existing sites, major (2.0.0) for anything that changes how existing sites look or
removes a setting. Look at the commits since the last tag (`git log v<last>..HEAD`) and
propose a number.

## 2. Bump it everywhere (on a branch, through a PR)

The build refuses to run unless these match:
- `plainleaf/style.css` → `Version:`
- `plainleaf/readme.txt` → `Stable tag:` (and a short `= x.y.z =` changelog line)
- `package.json` → `"version"`
- `CHANGELOG.md` → a new `## [x.y.z] - YYYY-MM-DD` section at the top, written for site
  owners: what changed for them, in plain words. Mention anything they need to do.

Run `make build` (checks versions, PHP syntax, JSON, contrast, builds `dist/`) and
`vendor/bin/phpcs`. Open a PR; the Security workflow must pass. Merge it.

## 3. Publish

On an up-to-date `main`: `make release`. It builds the zip and creates release `vX.Y.Z`
with `plainleaf-X.Y.Z.zip` attached and the CHANGELOG section as notes. (A release made
on the GitHub website also works: the Release zip workflow builds and attaches the zip.)

## 4. Check

- The release page shows `plainleaf-X.Y.Z.zip`.
- On a site: Dashboard > Updates > Check again shows the new version (the theme caches the
  check for 6 hours; updating clears it).
- Tell the user the release URL.

Never move or delete an existing tag or release; fix forward with a new patch version.

---
name: release
description: Ship a new version of the Plainleaf theme. Releases are automatic when theme changes are merged to main; this skill picks the right version bump (PR label), gets the PR through its checks, and confirms the GitHub release and the zip that sites auto-update from. Use when the user wants to release, ship, publish or version the theme.
---

# Release Plainleaf

Releases are cut by `.github/workflows/release.yml`, not by hand. Every push to `main` that
changes `plainleaf/` (a merged PR) publishes release `vX.Y.Z` with `plainleaf-X.Y.Z.zip`.
Every site with auto-updates installs it within about 12 hours, so a merge is
outward-facing: confirm with the user before merging.

Don't edit the `Version:` header, `Stable tag:` or `package.json` version in a PR. The
workflow works out the version from the release tags (`bin/next-version.sh`) and stamps it
into the build (`bin/stamp-version.sh`). The value in the repo is only the very first
version.

## 1. Pick the bump (a label on the PR)

Look at what the PR changes for site owners:

| Change | Label | Example |
| --- | --- | --- |
| Fixes, small tweaks, no new settings | none (patch) | 1.2.3 → 1.2.4 |
| New feature or setting that doesn't change existing sites | `release:minor` | 1.2.3 → 1.3.0 |
| Changes how existing sites look, or removes or renames a setting | `release:major` | 1.2.3 → 2.0.0 |
| Theme files changed but nothing site owners would notice | `release:skip` | no release |

Add it with `gh pr edit <number> --add-label release:minor`. Changes outside `plainleaf/`
(docs, CI, tooling) never release, so they need no label.

## 2. Get the PR green

`make build` and `vendor/bin/phpcs` locally; on GitHub the Security workflow (PHPCS and
Semgrep) must pass. The PR title becomes a line in the release notes, so make it read well
for site owners ("Add a hero video option", not "fix stuff").

## 3. Merge, then check

After the user confirms, merge (squash). Then:

- `gh run watch` (or the Actions tab): the Release workflow should finish green.
- `gh release view --json tagName,assets`: the new tag and `plainleaf-X.Y.Z.zip`.
- On a site: Dashboard > Updates > Check again shows it (the theme caches the check for 6
  hours; updating clears it).

Tell the user the version and the release URL.

## Releasing without a merge

`make release` (patch) or `make release BUMP=minor` starts the same workflow from the
current `main` (Actions tab > Release > Run workflow does the same).

## If something goes wrong

Never move or delete a published tag or release: sites may already have installed it. Fix
forward: merge the fix and let the next patch release go out. If the workflow failed before
publishing, fix the cause and re-run it from the Actions tab.

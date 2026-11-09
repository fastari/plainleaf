#!/usr/bin/env bash
# Publish the current theme version as a GitHub Release, from your own machine.
#
#   make release   (or: bash bin/release.sh)
#
# Run it on an up-to-date main after merging a version bump. It builds the zip
# (bin/build.sh checks versions, PHP, JSON and contrast), then creates release
# vX.Y.Z with the zip attached and the CHANGELOG entry as notes. Sites running
# Plainleaf find the release through inc/updater.php (public GitHub API, no
# token) and update themselves. If you create a release on GitHub instead, the
# Release workflow builds and attaches the zip for you.
#
# Needs the GitHub CLI (gh) signed in to an account that can write to the repo.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

VERSION="$(sed -n 's/^Version:[[:space:]]*//p' plainleaf/style.css | tr -d '\r')"
BRANCH="$(git rev-parse --abbrev-ref HEAD)"

if [[ "$BRANCH" != "main" ]]; then
	echo "Switch to main first (you are on $BRANCH)." >&2
	exit 1
fi
git fetch -q origin main
if [[ "$(git rev-parse HEAD)" != "$(git rev-parse origin/main)" ]]; then
	echo "Your main isn't the same as GitHub's. Run: git pull" >&2
	exit 1
fi
if gh release view "v$VERSION" >/dev/null 2>&1; then
	echo "v$VERSION is already released. Bump the version before releasing again." >&2
	exit 1
fi

bash bin/build.sh
NOTES="$(mktemp)"
awk -v v="$VERSION" '$0 ~ "^## \\[" v "\\]" {f=1; next} /^## \[/ {f=0} f' CHANGELOG.md > "$NOTES"
gh release create "v$VERSION" "dist/plainleaf-$VERSION.zip" \
	--title "v$VERSION" \
	--notes-file "$NOTES" \
	--target "$(git rev-parse HEAD)"
rm -f "$NOTES"
echo "Released v$VERSION. Sites with auto-updates on install it within about 12 hours (or click Update now in Dashboard > Updates)."

#!/usr/bin/env bash
# Print the next release version, worked out from the git tags.
#
#   bash bin/next-version.sh [patch|minor|major]   (default: patch)
#
# No vX.Y.Z tag yet: prints the Version: header from plainleaf/style.css (the
# first release). Otherwise bumps the highest tag. Needs the tags fetched
# (actions/checkout with fetch-depth: 0).
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")/.."

bump="${1:-patch}"
latest="$(git tag --list 'v[0-9]*.[0-9]*.[0-9]*' --sort=-v:refname | head -1)"

if [[ -z "$latest" ]]; then
	sed -n 's/^Version:[[:space:]]*//p' plainleaf/style.css | tr -d '\r'
	exit 0
fi

IFS=. read -r major minor patch <<< "${latest#v}"
case "$bump" in
	major) echo "$((major + 1)).0.0" ;;
	minor) echo "$major.$((minor + 1)).0" ;;
	patch) echo "$major.$minor.$((patch + 1))" ;;
	*) echo "Unknown bump '$bump' (use patch, minor or major)." >&2; exit 1 ;;
esac

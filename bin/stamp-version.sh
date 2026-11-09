#!/usr/bin/env bash
# Write a version into the files the build checks (style.css, readme.txt,
# package.json). Used by the Release workflow just before building, so the zip
# carries the released version. Not committed back to the repo: release tags
# are the source of truth for versions.
#
#   bash bin/stamp-version.sh 1.2.3
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")/.."

version="${1:-}"
if [[ ! "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
	echo "Usage: bash bin/stamp-version.sh X.Y.Z" >&2
	exit 1
fi

sed -i.bak "s/^Version:.*/Version: $version/" plainleaf/style.css
sed -i.bak "s/^Stable tag:.*/Stable tag: $version/" plainleaf/readme.txt
rm -f plainleaf/style.css.bak plainleaf/readme.txt.bak
# First "version" key only (the package's own version).
perl -0pi -e 's/("version"\s*:\s*")[^"]*(")/${1}'"$version"'${2}/' package.json
echo "Stamped $version"

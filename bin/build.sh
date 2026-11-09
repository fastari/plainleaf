#!/usr/bin/env bash
# Build an installable theme zip: dist/plainleaf-<version>.zip
#
# The version comes from the Version: header in plainleaf/style.css.
# The script refuses to build if the versions in style.css, readme.txt and
# package.json disagree, or if CHANGELOG.md has no entry for the version.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEME_SLUG="plainleaf"
THEME_DIR="$ROOT/$THEME_SLUG"
DIST="$ROOT/dist"

version_style="$(sed -n 's/^Version:[[:space:]]*//p' "$THEME_DIR/style.css" | tr -d '\r')"
version_readme="$(sed -n 's/^Stable tag:[[:space:]]*//p' "$THEME_DIR/readme.txt" | tr -d '\r')"
version_package="$(sed -n 's/^[[:space:]]*"version":[[:space:]]*"\([^"]*\)".*/\1/p' "$ROOT/package.json" | head -1)"

if [[ "$version_style" != "$version_readme" || "$version_style" != "$version_package" ]]; then
	echo "Version mismatch: style.css=$version_style readme.txt=$version_readme package.json=$version_package" >&2
	exit 1
fi

if ! grep -q "^## \[$version_style\]" "$ROOT/CHANGELOG.md"; then
	echo "CHANGELOG.md has no entry for $version_style" >&2
	exit 1
fi

# Basic checks before packaging.
while IFS= read -r -d '' file; do
	php -l "$file" > /dev/null
done < <(find "$THEME_DIR" -name '*.php' -print0)
find "$THEME_DIR/theme.json" "$THEME_DIR/styles" "$THEME_DIR/blocks" -name '*.json' -print0 \
	| xargs -0 python3 -c "import json,sys; [json.load(open(f)) for f in sys.argv[1:]]"
python3 "$ROOT/bin/check-contrast.py" > /dev/null

mkdir -p "$DIST"
ZIP="$DIST/$THEME_SLUG-$version_style.zip"
rm -f "$ZIP"

# Zip from the parent so the archive contains a single plainleaf/ folder,
# which is what Appearance > Themes > Add New > Upload expects.
(
	cd "$ROOT"
	zip -rq -X "$ZIP" "$THEME_SLUG" \
		-x "*.DS_Store" -x "*/.git*" -x "*/node_modules/*" -x "*.map" -x "*/Thumbs.db"
)

echo "Built $ZIP"
unzip -l "$ZIP" | tail -1

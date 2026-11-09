#!/usr/bin/env bash
# Fail if any tracked file matches a pattern from a private denylist: names,
# colors, IDs or other things from a private site that must never land in this
# public theme. The denylist is NOT in this repo.
#
#   Local:  PLAINLEAF_DENYLIST_FILE=~/path/to/denylist.txt bash bin/check-brand.sh
#   CI:     the BRAND_DENYLIST repository secret (one extended regex per line).
#
# With no denylist available (for example on a pull request from a fork), it
# says so and passes.
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")/.."

list="$(mktemp)"
trap 'rm -f "$list"' EXIT
if [[ -n "${BRAND_DENYLIST:-}" ]]; then
	printf '%s\n' "$BRAND_DENYLIST" > "$list"
elif [[ -n "${PLAINLEAF_DENYLIST_FILE:-}" && -f "$PLAINLEAF_DENYLIST_FILE" ]]; then
	cp "$PLAINLEAF_DENYLIST_FILE" "$list"
else
	echo "No denylist available; skipping the brand check."
	exit 0
fi
# Drop empty lines and comments.
sed -i.bak -e '/^[[:space:]]*$/d' -e '/^[[:space:]]*#/d' "$list" && rm -f "$list.bak"
if [[ ! -s "$list" ]]; then
	echo "Denylist is empty; skipping the brand check."
	exit 0
fi

# Text files only. Print only file:line, never the matching text, so CI logs
# don't reveal what the private list contains.
hits="$(git ls-files -z | xargs -0 grep -nIiE -f "$list" -- 2>/dev/null | cut -d: -f1,2 || true)"
if [[ -n "$hits" ]]; then
	printf '%s\n' "$hits"
	echo "Found text from the private denylist above. Remove it before merging." >&2
	exit 1
fi
echo "Brand check passed: $(wc -l < "$list" | tr -d ' ') patterns, $(git ls-files | wc -l | tr -d ' ') files."

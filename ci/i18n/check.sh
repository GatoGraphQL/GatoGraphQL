#!/usr/bin/env bash
#
# Release gate for the Gato GraphQL base translations. Fails if the base POT is
# stale (source strings changed without re-extracting) or any locale's master PO
# has untranslated/fuzzy entries.
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT"

LANG_DIR="layers/GatoGraphQLForWP/plugins/gatographql/languages"
POT="$LANG_DIR/gatographql.pot"
LOCALES_FILE="ci/i18n/locales.txt"
EXCLUDES="tests,node_modules,phpunit-plugins,phpunit-packages,vendor,testing-schema"

wp_i18n() { WP_CLI_PHP_ARGS='-d memory_limit=2048M' vendor/bin/wp "$@"; }

fail=0

# 1) POT freshness
tmp="$(mktemp)"
wp_i18n i18n make-pot layers "$tmp" \
    --domain=gatographql --skip-audit --skip-js --exclude="$EXCLUDES" >/dev/null 2>&1
if [ ! -f "$POT" ] || ! diff -q \
        <(grep -v '^"POT-Creation-Date' "$POT") \
        <(grep -v '^"POT-Creation-Date' "$tmp") >/dev/null 2>&1; then
    echo "ERROR: base POT is stale (source strings changed). Run: composer i18n-extract"
    fail=1
fi
rm -f "$tmp"

# 2) completeness per locale
while IFS= read -r locale; do
    [ -z "$locale" ] && continue
    po="$LANG_DIR/gatographql-$locale.po"
    if [ ! -f "$po" ]; then
        echo "ERROR: missing base PO for $locale ($po). Run: composer i18n-extract"
        fail=1
        continue
    fi
    stats=$(msgfmt --statistics -o /dev/null "$po" 2>&1 || true)
    if echo "$stats" | grep -qE "fuzzy|untranslated"; then
        echo "ERROR: $locale incomplete ($stats). Run: composer i18n-translate"
        fail=1
    fi
done < "$LOCALES_FILE"

if [ "$fail" -eq 0 ]; then
    echo "i18n check passed."
fi
exit "$fail"

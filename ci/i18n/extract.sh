#!/usr/bin/env bash
#
# Extract the Gato GraphQL base translations.
#
# Produces a single POT for the whole open-source plugin under the "gatographql"
# text domain (one master, since every package now shares that domain), then
# creates/updates one master PO per target locale. JS strings are out of scope
# here (handled separately via @wordpress/i18n + make-json).
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT"

LANG_DIR="layers/GatoGraphQLForWP/plugins/gatographql/languages"
POT="$LANG_DIR/gatographql.pot"
LOCALES_FILE="ci/i18n/locales.txt"
EXCLUDES="tests,node_modules,phpunit-plugins,phpunit-packages,vendor,testing-schema"

wp_i18n() { WP_CLI_PHP_ARGS='-d memory_limit=2048M' vendor/bin/wp "$@"; }

mkdir -p "$LANG_DIR"

echo "==> Extracting base POT: $POT"
wp_i18n i18n make-pot layers "$POT" \
    --domain=gatographql --skip-audit --skip-js --exclude="$EXCLUDES"
echo "    $(grep -c '^msgid "' "$POT") entries"

echo "==> Updating per-locale master PO"
while IFS= read -r locale; do
    [ -z "$locale" ] && continue
    po="$LANG_DIR/gatographql-$locale.po"
    if [ -f "$po" ]; then
        msgmerge --quiet --update --backup=none --no-fuzzy-matching "$po" "$POT"
    else
        msginit --no-translator --no-wrap --locale="$locale" --input="$POT" --output-file="$po"
    fi
    echo "    $po"
done < "$LOCALES_FILE"

bash ci/i18n/normalize-dates.sh "$POT" "$LANG_DIR"/gatographql-*.po

echo "==> Base extraction done."

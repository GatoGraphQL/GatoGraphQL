#!/usr/bin/env bash
#
# Compile the Gato GraphQL base translations. The base plugin ships the whole
# catalog, so there is no per-plugin filtering: each locale's master PO is
# compiled directly to gatographql-<locale>.mo (+ .l10n.php) in the plugin's
# languages/ folder. Only translated entries land in the .mo.
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT"

LANG_DIR="layers/GatoGraphQLForWP/plugins/gatographql/languages"
LOCALES_FILE="ci/i18n/locales.txt"

wp_i18n() { WP_CLI_PHP_ARGS='-d memory_limit=2048M' vendor/bin/wp "$@"; }

while IFS= read -r locale; do
    [ -z "$locale" ] && continue
    po="$LANG_DIR/gatographql-$locale.po"
    [ -f "$po" ] || continue
    msgfmt "$po" -o "$LANG_DIR/gatographql-$locale.mo"
    wp_i18n i18n make-php "$po" "$LANG_DIR/" >/dev/null 2>&1 || true
    echo "  gatographql-$locale.mo"
done < "$LOCALES_FILE"

echo "==> Base compile done."

#!/usr/bin/env bash
#
# Translate the Gato GraphQL base master PO for every locale, via Claude (Sonnet).
# Only untranslated entries are sent (DRY). Flags pass through to translate-po.php
# (e.g. `bash ci/i18n/translate.sh --limit=100`).
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT"

LANG_DIR="layers/GatoGraphQLForWP/plugins/gatographql/languages"
LOCALES_FILE="ci/i18n/locales.txt"

failed=0
while IFS= read -r locale; do
    [ -z "$locale" ] && continue
    po="$LANG_DIR/gatographql-$locale.po"
    if [ ! -f "$po" ]; then
        echo "SKIP $locale: $po not found — run 'composer i18n-extract' first."
        continue
    fi
    # Don't let one locale's residual-failure abort the whole loop (set -e).
    if ! php ci/i18n/translate-po.php "$po" --locale="$locale" "$@"; then
        failed=1
    fi
done < "$LOCALES_FILE"

if [ "$failed" -ne 0 ]; then
    echo "ERROR: some locales still have untranslated entries (see warnings above); re-run to retry." >&2
    exit 1
fi

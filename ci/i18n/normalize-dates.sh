#!/usr/bin/env bash
#
# Strip volatile timestamps from generated i18n files (POT-Creation-Date /
# PO-Revision-Date in .po/.pot, pot-creation-date / po-revision-date in
# .l10n.php) so re-running extract/compile doesn't churn git when nothing
# actually changed. The release gate already ignores POT-Creation-Date, so
# blanking these has no functional effect.
#
# Usage:
#   bash ci/i18n/normalize-dates.sh <file> [<file> ...]   # normalize given files
#   bash ci/i18n/normalize-dates.sh                       # normalize all i18n files
#
set -uo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

normalize_one() {
    local f="$1"
    case "$f" in
        *.pot)
            # In a .pot, only POT-Creation-Date is volatile; PO-Revision-Date is a
            # constant template placeholder that make-pot always emits — leave it.
            perl -i -pe 's/^"(POT-Creation-Date):.*/"$1: \\n"/' "$f"
            ;;
        *.po)
            perl -i -pe 's/^"(POT-Creation-Date|PO-Revision-Date):.*/"$1: \\n"/' "$f"
            ;;
        *.l10n.php)
            perl -i -pe "s/'(pot-creation-date|po-revision-date)'=>'[^']*'/'\$1'=>''/g" "$f"
            ;;
    esac
}

if [ "$#" -gt 0 ]; then
    for f in "$@"; do
        [ -f "$f" ] && normalize_one "$f"
    done
else
    while IFS= read -r f; do
        normalize_one "$f"
    done < <(find "$ROOT/layers" \
                  "$ROOT/ci/i18n/master" \
                  "$ROOT/submodules/ExtensionStarter/submodules/GatoGraphQL/layers" \
                  \( -name '*.po' -o -name '*.pot' -o -name '*.l10n.php' \) 2>/dev/null \
             | grep -vE '/(vendor|node_modules)/')
fi

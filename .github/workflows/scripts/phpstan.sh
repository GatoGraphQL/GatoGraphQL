#!/bin/bash
########################################################################
#
# Script to execute PHPStan on a series of packages
# Provide the packages as the first argument to the script,
# separated by a space:
# scripts/phpstan.sh "package1 package2 package3"
#
########################################################################
# Helper functions
# ----------------------------------------------------------------------
function fail {
    set -e
    printf '%s\n' "$1" >&2  ## Send message to stderr. Exclude >&2 if you don't want it that way.
    exit "${2-1}"  ## Return a code specified by $2 or 1 by default.
}
function join_by { local d=$1; shift; local f=$1; shift; printf %s "$f" "${@/#/$d}"; }
function note {
    MESSAGE=$1;

    printf "\n";
    echo "[NOTE] $MESSAGE";
    printf "\n";
}
########################################################################

# Do not show errors! Or otherwise the script ends whenever PHPStan fails on any package
set +e

########################################################################

# Variables
PACKAGES="$1"
if [ -z "$PACKAGES" ]; then
    fail "Please provide to packages to execute PHPStan, as first argument to the bash script"
fi

########################################################################

failed_packages=()
packages=($PACKAGES)
for package in "${packages[@]}"
do
    note "Executing tests for package '${package}'"
    vendor/bin/phpstan analyse $package/src $package/tests -c $package/phpstan.neon.dist --ansi
    if [ "$?" -gt 0 ]; then
        failed_packages+=($package)
    fi
done
if [ ${#failed_packages[@]} -gt 0 ]; then
    package_list=$(join_by "\n" ${failed_packages[@]})
    fail "❌ PHPStan failed on packages:\n${package_list}"
fi
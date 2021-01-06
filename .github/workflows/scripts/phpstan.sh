#!/bin/bash
########################################################################
# Helper functions
# ----------------------------------------------------------------------
function fail {
    printf '%s\n' "$1" >&2  ## Send message to stderr. Exclude >&2 if you don't want it that way.
    exit "${2-1}"  ## Return a code specified by $2 or 1 by default.
}
function join_by { local d=$1; shift; local f=$1; shift; printf %s "$f" "${@/#/$d}"; }
########################################################################

# show errors
set -e

########################################################################

# VARIABLES
PACKAGES="$1"
if [ -z "$PACKAGES" ]; then
    fail "Please provide to packages to execute PHPStan, as first argument to the bash script"
fi

########################################################################

failed_packages=()
packages=($PACKAGES)
for package in "${packages[@]}"
do
    vendor/bin/phpstan analyse $package/src $package/tests -c $package/phpstan.neon.dist
    if [ "$?" -gt 0 ]; then
        failed_packages+=($package)
    fi
done
if [ ${#failed_packages[@]} -gt 0 ]; then
    package_list=$(join_by ", " ${failed_packages[@]})
    fail "PHPStan failed on packages: ${package_list}"
fi
#!/bin/bash
########################################################################
#
# This bash script downgrades the code for the project
# and all its dependencies to PHP 7.1
#
########################################################################

########################################################################
# Helper functions
# ----------------------------------------------------------------------

# Failure helper function (https://stackoverflow.com/a/24597941)
function fail {
    printf '%s\n' "$1" >&2  ## Send message to stderr. Exclude >&2 if you don't want it that way.
    exit "${2-1}"  ## Return a code specified by $2 or 1 by default.
}

# Print array helpers (https://stackoverflow.com/a/17841619)
function join_by { local d=$1; shift; local f=$1; shift; printf %s "$f" "${@/#/$d}"; }

# Print a message in the output
function note {
    MESSAGE=$1;

    printf "\n";
    echo "[NOTE] $MESSAGE";
    printf "\n";
}

########################################################################

# Fail fast
set -e

########################################################################
# Inputs
# ----------------------------------------------------------------------

rector_config="$1"
rector_options="$2"
target_php_version="$3"
local_owners="$4"

default_local_owners="leoloso getpop pop-schema graphql-by-pop graphql-api pop-sites-wassup"

########################################################################
# Validate inputs
# ----------------------------------------------------------------------

if [ -z "$rector_config" ]; then
    fail "Please provide to path to the Rector config file"
fi

########################################################################
# Initialize defaults
# ----------------------------------------------------------------------

local_package_owners=(${local_owners:=$default_local_owners})

########################################################################
# Logic
# ----------------------------------------------------------------------

packages_to_downgrade=()
package_paths=()
rootPackage=$(composer info -s -N)

# Downgrade only packages that need a higher version that the input one, or all of them
if [ -n "$target_php_version" ]; then
    why_not_version="${target_php_version}.*"
    # Switch to production, to calculate the packages
    composer install --no-dev --no-progress --ansi
    PACKAGES=$(composer why-not php "$why_not_version" --no-interaction | grep -o "\S*\/\S*")
    # Switch to dev again
    composer install --no-progress --ansi
else
    PACKAGES=$(composer info --name-only --no-dev)
fi

# Ignore all the "migrate" packages
for local_package_owner in ${local_package_owners[@]}
do
    PACKAGES=$(echo "$PACKAGES" | awk "!/${local_package_owner}\/migrate-/")
done

if [ -n "$PACKAGES" ]; then
    for package in $PACKAGES
    do
        # Composer also analyzes the root project but its path is directly the root folder
        if [ $package = "$rootPackage" ]
        then
            # downgrade the src/ folder only (i.e. make sure to avoid vendors/)
            path=$(pwd)
        else
            # Obtain the package's path from Composer
            # Format is "package path", so extract everything
            # after the 1st word with cut to obtain the path
            path=$(composer info $package --path | cut -d' ' -f2-)
        fi

        # Optmization: For local dependencies, only analyze src/
        package_owner=$(echo "$package" | grep -o "\S*\/" | rev | cut -c2- | rev )
        if [[ " ${local_package_owners[@]} " =~ " ${package_owner} " ]]; then
            path="$path/src"
        fi

        packages_to_downgrade+=($package)
        package_paths+=($path)
        note "[Package to downgrade] $package (under '$path')"
    done
else
    note "No packages to downgrade"
    exit 0
fi

# Execute the downgrade
packages=$(join_by " " ${packages_to_downgrade[@]})
paths=$(join_by " " ${package_paths[@]})
vendor/bin/rector process $paths --config=$rector_config --ansi $rector_options

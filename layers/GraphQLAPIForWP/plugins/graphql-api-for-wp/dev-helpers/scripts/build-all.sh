#!/bin/bash
# This bash script generates the JS build,
# by running `npm run build` on all scripts (blocks/editor-scripts)

# Current directory
# @see: https://stackoverflow.com/questions/59895/how-to-get-the-source-directory-of-a-bash-script-from-within-the-script-itself#comment16925670_59895
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"

# Function `buildScripts` will run `npm run build`
# on all folders in the current directory
buildScripts(){
    CURRENT_DIR=$( pwd )
    echo "In folder '$CURRENT_DIR'"
    for file in ./*
    do
        # Make sure it is a directory
        if [ -d "$file" ]; then
            echo "In subfolder '$file'"
            cd "$file"
            # Install node_modules/ dependencies
            npm install --legacy-peer-deps
            npm run build
            cd ..
        fi
    done
}

# # First create the symlinks to node_modules/ everywhere
# bash -x "$DIR/create-node-modules-symlinks.sh" >/dev/null 2>&1

# Packages: used by Blocks/Editor Scripts
cd "$DIR/../../packages/"
buildScripts

# Blocks
cd "$DIR/../../blocks/"
buildScripts

# Editor Scripts
cd "$DIR/../../editor-scripts/"
buildScripts

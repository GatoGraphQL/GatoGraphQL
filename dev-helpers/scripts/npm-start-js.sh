#!/bin/bash
# This bash script generates the JS build for DEV,
# by running `npm run start` on all scripts (blocks/editor-scripts)

# Silent
set +x

# Current directory
# @see: https://stackoverflow.com/questions/59895/how-to-get-the-source-directory-of-a-bash-script-from-within-the-script-itself#comment16925670_59895
# DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
PWD="$( pwd )"

# Must pass the path to the plugin root as first arg to the script
PLUGIN_DIR="$PWD/$1"
if [ -z "$PLUGIN_DIR" ]; then
    echo "The path to the plugin directory is missing; pass it as first argument to the script"
else
    echo "Building all JS packages, blocks and editor scripts in path '$PLUGIN_DIR'"
fi

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
            ttab 'npm start'
            cd ..
        fi
    done
}

# Function `maybeBuildScripts` will invoke `buildScripts`
# if the target folder exists
maybeBuildScripts(){
    if [[ -d "$TARGET_DIR" ]]
    then
        cd "$TARGET_DIR"
        buildScripts
    else
        echo "Directory '$TARGET_DIR' does not exist"
    fi
}

# Packages: used by Blocks/Editor Scripts
TARGET_DIR="$PLUGIN_DIR/packages/"
maybeBuildScripts

# Blocks
TARGET_DIR="$PLUGIN_DIR/blocks/"
maybeBuildScripts

# Editor Scripts
TARGET_DIR="$PLUGIN_DIR/editor-scripts/"
maybeBuildScripts


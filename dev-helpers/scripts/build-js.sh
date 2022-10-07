#!/bin/bash
# This bash script generates the JS build,
# by running `npm run build` on all scripts (blocks/editor-scripts)

# Silent
set +x

# Current directory
# @see: https://stackoverflow.com/questions/59895/how-to-get-the-source-directory-of-a-bash-script-from-within-the-script-itself#comment16925670_59895
# DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
PWD="$( pwd )"

########################################################################
# Inputs
# ----------------------------------------------------------------------
# Must pass the path to the plugin root as first arg to the script
PLUGIN_DIR="$PWD/$1"
# Pass the command to execute:
# - BUILD_PROD: run `npm build` for all blocks
# - COMPILE_DEV: run `npm start` for all blocks in a new tab
# - INSTALL_DEPS: run `npm install --legacy-peers` for all blocks
COMMAND="$2"
########################################################################

if [ -z "$PLUGIN_DIR" ]; then
    fail "The path to the plugin directory is missing; pass it as first argument to the script"
fi

if [ -z "$COMMAND" ]; then
    fail "Please provide the Command [BUILD_PROD, COMPILE_DEV, INSTALL_DEPS]"
fi

# Show message
if [ $COMMAND = "BUILD_PROD" ]
then
    echo "Building all JS packages, blocks and editor scripts in path '$PLUGIN_DIR'"
elif [ $COMMAND = "COMPILE_DEV" ]
then
    echo "Compiling all JS packages, blocks and editor scripts in path '$PLUGIN_DIR'. Using `ttab` to open multiple tabs. See: https://www.npmjs.com/package/ttab"
elif [ $COMMAND = "INSTALL_DEPS" ]
then
    echo "Installing dependencies for all JS packages, blocks and editor scripts in path '$PLUGIN_DIR'"
fi

# Function `runCommand` will run the selected command
# on all folders in the current directory
runCommand(){
    CURRENT_DIR=$( pwd )
    echo "In folder '$CURRENT_DIR'"
    for file in ./*
    do
        # Make sure it is a directory
        if [ -d "$file" ]; then
            echo "In subfolder '$file'"
            cd "$file"
            if [ $COMMAND = "BUILD_PROD" ]
            then
                npm run build
            elif [ $COMMAND = "COMPILE_DEV" ]
            then
                ttab 'npm start'
            elif [ $COMMAND = "INSTALL_DEPS" ]
            then
                npm install --legacy-peer-deps
            fi
            cd ..
        fi
    done
}

# Function `maybeRunCommandInTargetDirectory` will invoke `runCommand`
# if the target folder exists
maybeRunCommandInTargetDirectory(){
    if [[ -d "$TARGET_DIR" ]]
    then
        cd "$TARGET_DIR"
        runCommand
    else
        echo "Directory '$TARGET_DIR' does not exist"
    fi
}

# Packages: used by Blocks/Editor Scripts
TARGET_DIR="$PLUGIN_DIR/packages/"
maybeRunCommandInTargetDirectory

# Blocks
TARGET_DIR="$PLUGIN_DIR/blocks/"
maybeRunCommandInTargetDirectory

# Editor Scripts
TARGET_DIR="$PLUGIN_DIR/editor-scripts/"
maybeRunCommandInTargetDirectory


#!/bin/bash
# Create the symlinks from node_modules/ in the current folder,
# to all the blocks/editor-scripts in the plugin
# Then, a single node_modules/ folder can service everything
# Make sure package.json contains ALL dependencies needed for everything

# Current directory
# @see: https://stackoverflow.com/questions/59895/how-to-get-the-source-directory-of-a-bash-script-from-within-the-script-itself#comment16925670_59895
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"

# node_modules/ directory, under packages/
NODE_MODULES_DIR="$DIR/../packages/node_modules/"

# Function `createSymlinks` will create a 'node_modules/' symlink
# for all folders in the current directory
createSymlinks(){
    CURRENT_DIR=$( pwd )
    echo "In folder '$CURRENT_DIR'"
    for file in ./*
    do
        # Make sure it is a directory
        if [ -d "$file" ]; then
            echo "Maybe creating symlink for '$file'"
            # Create symlink
            ln -snf "$NODE_MODULES_DIR" "$file"
        fi
    done
}

# Blocks
cd "$DIR/../../blocks/"
createSymlinks

# Editor Scripts
cd "$DIR/../../editor-scripts/"
createSymlinks

# # Packages
# cd "$DIR/../../packages/"
# createSymlinks

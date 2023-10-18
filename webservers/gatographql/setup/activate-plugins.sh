#!/bin/sh
# Download and maybe activate external plugins
if wp plugin is-installed wordpress-importer; then
    wp plugin activate wordpress-importer
else
    wp plugin install wordpress-importer --activate
fi
if ! wp plugin is-installed classic-editor; then
    wp plugin install classic-editor
fi

# Activate own plugins
wp plugin activate gatographql
wp plugin activate gatographql-testing-schema
wp plugin activate gatographql-testing

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

# Install/activate own plugins
if wp plugin is-installed gatographql; then
    wp plugin activate gatographql
else
    wp plugin install https://gatographql.com/releases/latest/gatographql.zip --force --activate
fi
if wp plugin is-installed gatographql-testing-schema; then
    wp plugin activate gatographql-testing-schema
else
    wp plugin install https://gatographql.com/releases/latest/gatographql-testing-schema.zip --force --activate
fi
if wp plugin is-installed gatographql-testing; then
    wp plugin activate gatographql-testing
else
    wp plugin install https://gatographql.com/releases/latest/gatographql-testing.zip --force --activate
fi

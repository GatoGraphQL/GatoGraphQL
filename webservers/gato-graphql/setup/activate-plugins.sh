#!/bin/sh
# Download and maybe activate external plugins
if wp plugin is-installed wordpress-importer --path=/app/wordpress; then
    wp plugin activate wordpress-importer --path=/app/wordpress
else
    wp plugin install wordpress-importer --activate --path=/app/wordpress
fi
if ! wp plugin is-installed classic-editor --path=/app/wordpress; then
    wp plugin install classic-editor --path=/app/wordpress
fi

# Activate own plugins
wp plugin activate gatographql --path=/app/wordpress
wp plugin activate gatographql-testing-schema --path=/app/wordpress
wp plugin activate gatographql-testing --path=/app/wordpress

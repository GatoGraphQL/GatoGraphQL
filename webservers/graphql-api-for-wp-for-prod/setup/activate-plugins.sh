#!/bin/sh
# Download and activate external plugins
wp plugin install wordpress-importer --activate --path=/app/wordpress
# Install/activate own plugins
if wp plugin is-installed graphql-api --path=/app/wordpress; then
    wp plugin activate graphql-api --path=/app/wordpress
else
    wp plugin install https://github.com/leoloso/PoP/releases/latest/download/graphql-api.zip --force --activate --path=/app/wordpress
fi
if wp plugin is-installed graphql-api-testing --path=/app/wordpress; then
    wp plugin activate graphql-api-testing --path=/app/wordpress
else
    wp plugin install https://github.com/leoloso/PoP/releases/latest/download/graphql-api-testing.zip --force --activate --path=/app/wordpress
fi

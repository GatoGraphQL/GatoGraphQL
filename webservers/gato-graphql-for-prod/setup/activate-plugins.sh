#!/bin/sh
# Download and activate external plugins
wp plugin install wordpress-importer --activate --path=/app/wordpress
# Install own plugins
if ! wp plugin is-installed gato-graphql --path=/app/wordpress; then
    wp plugin install https://github.com/leoloso/PoP/releases/latest/download/gato-graphql.zip --force --activate --path=/app/wordpress
fi
if ! wp plugin is-installed gato-graphql-testing-schema --path=/app/wordpress; then
    wp plugin install https://github.com/leoloso/PoP/releases/latest/download/gato-graphql-testing-schema.zip --force --activate --path=/app/wordpress
fi
if ! wp plugin is-installed gato-graphql-testing --path=/app/wordpress; then
    wp plugin install https://github.com/leoloso/PoP/releases/latest/download/gato-graphql-testing.zip --force --activate --path=/app/wordpress
fi
# Activate own plugins
wp plugin activate gato-graphql gato-graphql-testing-schema gato-graphql-testing --path=/app/wordpress
#!/bin/sh
# Download and activate external plugins
wp plugin install wordpress-importer --activate --path=/app/wordpress
# Activate own plugins
wp plugin activate graphql-api --path=/app/wordpress
wp plugin activate graphql-api-testing --path=/app/wordpress

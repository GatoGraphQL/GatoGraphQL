#!/bin/sh
# Activate own plugins
wp plugin activate graphql-api --path=/app/wordpress
# Download and activate external plugins
wp plugin install wordpress-importer --activate --path=/app/wordpress

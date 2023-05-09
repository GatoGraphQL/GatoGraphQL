#!/bin/sh
# Download and activate external plugins
wp plugin install wordpress-importer --activate --path=/app/wordpress
# Activate own plugins
wp plugin activate gato-graphql --path=/app/wordpress
wp plugin activate gato-graphql-testing-schema --path=/app/wordpress
wp plugin activate gato-graphql-testing --path=/app/wordpress

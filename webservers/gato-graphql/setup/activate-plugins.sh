#!/bin/sh
# Download and activate external plugins
wp plugin install wordpress-importer --activate --path=/app/wordpress
# Activate own plugins
wp plugin activate gato-graphql gato-graphql-testing-schema gato-graphql-testing --path=/app/wordpress

#!/bin/sh
wp plugin install wordpress-importer --activate --path=/app/wordpress
wp import /app/assets/graphql-api-sample-data.xml --authors=create --path=/app/wordpress
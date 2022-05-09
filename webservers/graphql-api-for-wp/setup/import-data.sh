#!/bin/sh
# First remove the first "Hello world!" post (and "Sample Page" and "Privacy Policy" pages), to avoid duplication (it's in the dataset)
wp post delete $(wp post list --post_type='post,page' --format=ids --path=/app/wordpress) --force --path=/app/wordpress
wp import /app/assets/graphql-api-data.xml --authors=create --path=/app/wordpress
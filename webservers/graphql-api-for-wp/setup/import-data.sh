#!/bin/sh
# First remove the first "Hello world!" post, to avoid duplication (it's in the dataset)
wp post delete 1 --force --path=/app/wordpress
wp import /app/assets/graphql-api-data.xml --authors=create --path=/app/wordpress
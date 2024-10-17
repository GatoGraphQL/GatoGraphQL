#!/bin/bash
# Install own plugins
if ! wp plugin is-installed gatographql; then
    wp plugin install https://gatographql.com/releases/latest/gatographql.zip --force
fi
if ! wp plugin is-installed gatographql-testing-schema; then
    wp plugin install https://gatographql.com/releases/latest/gatographql-testing-schema.zip --force
fi
if ! wp plugin is-installed gatographql-testing; then
    wp plugin install https://gatographql.com/releases/latest/gatographql-testing.zip --force --activate
fi

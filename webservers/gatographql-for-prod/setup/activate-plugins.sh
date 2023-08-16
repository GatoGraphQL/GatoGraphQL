#!/bin/sh
# Download and maybe activate external plugins
if wp plugin is-installed wordpress-importer --path=/app/wordpress; then
    wp plugin activate wordpress-importer --path=/app/wordpress
else
    wp plugin install wordpress-importer --activate --path=/app/wordpress
fi
if ! wp plugin is-installed classic-editor --path=/app/wordpress; then
    wp plugin install classic-editor --path=/app/wordpress
fi

# Install/activate own plugins
if wp plugin is-installed gatographql --path=/app/wordpress; then
    wp plugin activate gatographql --path=/app/wordpress
else
    wp plugin install https://github.com/GatoGraphQL/GatoGraphQL/releases/latest/download/gatographql.zip --force --activate --path=/app/wordpress
fi
if wp plugin is-installed gatographql-testing-schema --path=/app/wordpress; then
    wp plugin activate gatographql-testing-schema --path=/app/wordpress
else
    wp plugin install https://github.com/GatoGraphQL/GatoGraphQL/releases/latest/download/gatographql-testing-schema.zip --force --activate --path=/app/wordpress
fi
if wp plugin is-installed gatographql-testing --path=/app/wordpress; then
    wp plugin activate gatographql-testing --path=/app/wordpress
else
    wp plugin install https://github.com/GatoGraphQL/GatoGraphQL/releases/latest/download/gatographql-testing.zip --force --activate --path=/app/wordpress
fi

#!/bin/sh
echo "Configuring the Gato GraphQL plugin settings"
echo "Selecting \"dummy\" CPT, categories and tags"

ADMIN_USER_APP_PASSWORD=$(wp user meta get 1 app_password --path=/app/wordpress)
SITE_DOMAIN=$(wp option get siteurl --path=/app/wordpress)

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"custompost-types\":[\"dummy-cpt\",\"page\",\"post\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/gato-graphql/v1/admin/module-settings/gatographql_gatographql_schema-customposts

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"tag-taxonomies\":[\"dummy-tag\",\"post_tag\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/gato-graphql/v1/admin/module-settings/gatographql_gatographql_schema-tags

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"category-taxonomies\":[\"category\",\"dummy-category\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/gato-graphql/v1/admin/module-settings/gatographql_gatographql_schema-categories
 
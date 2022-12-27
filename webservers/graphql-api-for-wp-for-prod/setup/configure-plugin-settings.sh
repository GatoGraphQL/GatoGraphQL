#!/bin/sh
echo "Configuring the GraphQL API plugin settings"
echo "Selecting \"dummy\" CPT, categories and tags"

ADMIN_USER_APP_PASSWORD=$(wp user meta get 1 app_password --path=/app/wordpress)
SITE_DOMAIN=$(wp option get siteurl --path=/app/wordpress)

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"custompost-types\":[\"page\",\"post\",\"dummy-cpt\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/graphql-api/v1/admin/module-settings/graphqlapi_graphqlapi_schema-customposts

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"tag-taxonomies\":[\"post_tag\",\"dummy-tag\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/graphql-api/v1/admin/module-settings/graphqlapi_graphqlapi_schema-tags

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"category-taxonomies\":[\"category\",\"dummy-category\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/graphql-api/v1/admin/module-settings/graphqlapi_graphqlapi_schema-categories
 
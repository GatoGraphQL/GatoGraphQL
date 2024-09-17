#!/bin/bash
echo Configuring the Gato GraphQL plugin settings
echo Enabling modules for DEV

ADMIN_USER_APP_PASSWORD=$(wp user meta get 1 app_password)
# Using "localhost:89347" is not visible from within the container, so point straight to localhost, it always works
# Remove the port from the webserver URL
SITE_DOMAIN=$(wp option get siteurl | grep -Eo "(http|https)://[a-zA-Z0-9_\.-]*")
# SITE_DOMAIN=$(wp option get siteurl)
# SITE_DOMAIN=https://localhost

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"state": "enabled"}' \
  $(echo $SITE_DOMAIN)/wp-json/gatographql/v1/admin/modules/gatographql_gatographql_schema-configuration

# Already set as "enabled" by default
# curl -i --insecure \
#   --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
#   -X POST \
#   -H "Content-Type: application/json" \
#   -d '{"state": "enabled"}' \
#   $(echo $SITE_DOMAIN)/wp-json/gatographql/v1/admin/modules/gatographql_gatographql_single-endpoint


echo Selecting "dummy" CPT, categories and tags

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"custompost-types\":[\"dummy-cpt\",\"dummy-cpt-two\",\"page\",\"post\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/gatographql/v1/admin/module-settings/gatographql_gatographql_schema-customposts

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"tag-taxonomies\":[\"additional-post-tag\",\"additional-dummy-tag\",\"dummy-tag\",\"dummy-tag-two\",\"post_tag\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/gatographql/v1/admin/module-settings/gatographql_gatographql_schema-tags

curl -i --insecure \
  --user "admin:$(echo $ADMIN_USER_APP_PASSWORD)" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"jsonEncodedOptionValues": "{\"category-taxonomies\":[\"additional-post-category\",\"additional-dummy-category\",\"category\",\"dummy-category\",\"dummy-category-two\"]}"}' \
  $(echo $SITE_DOMAIN)/wp-json/gatographql/v1/admin/module-settings/gatographql_gatographql_schema-categories
 
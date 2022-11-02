#!/bin/sh
wp core install --url="graphql-api-for-prod.lndo.site" --title="GraphQL API for PROD (PHP 7.1 + Generated .zip plugins)" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --path=/app/wordpress 
wp option update siteurl "https://graphql-api-for-prod.lndo.site" --path=/app/wordpress 
wp option update home "https://graphql-api-for-prod.lndo.site" --path=/app/wordpress 

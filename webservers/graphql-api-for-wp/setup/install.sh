#!/bin/sh
wp core install --url="graphql-api.lndo.site" --title="GraphQL API" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --path=/app/wordpress 
wp option update siteurl "https://graphql-api.lndo.site" --path=/app/wordpress 
wp option update home "https://graphql-api.lndo.site" --path=/app/wordpress 

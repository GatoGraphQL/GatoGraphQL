#!/bin/sh
wp core install --url="gatographql.lndo.site" --title="Gato GraphQL" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --path=/app/wordpress 
wp option update siteurl "https://gatographql.lndo.site" --path=/app/wordpress 
wp option update home "https://gatographql.lndo.site" --path=/app/wordpress 
# This change is needed on InstaWP
# wp option update admin_email "admin@example.com" --path=/app/wordpress

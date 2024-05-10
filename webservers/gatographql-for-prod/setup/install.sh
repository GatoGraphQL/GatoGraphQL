#!/bin/bash
wp core install --url="gatographql-for-prod.lndo.site" --title="Gato GraphQL demo site" --admin_user=admin --admin_password=admin --admin_email=admin@example.com 
wp option update siteurl "https://gatographql-for-prod.lndo.site" 
wp option update home "https://gatographql-for-prod.lndo.site" 
# This change is needed on InstaWP
# wp option update admin_email "admin@example.com"

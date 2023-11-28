#!/bin/bash
wp core install --url="gatographql.lndo.site" --title="Gato GraphQL" --admin_user=admin --admin_password=admin --admin_email=admin@example.com 
wp option update siteurl "https://gatographql.lndo.site" 
wp option update home "https://gatographql.lndo.site" 
# This change is needed on InstaWP
# wp option update admin_email "admin@example.com"

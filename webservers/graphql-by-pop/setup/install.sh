#!/bin/sh
wp core install --url="$SITE_NAME.$LANDO_DOMAIN" --title="$SITE_TITLE" --admin_user=admin --admin_password=admin --admin_email=admin@example.com 

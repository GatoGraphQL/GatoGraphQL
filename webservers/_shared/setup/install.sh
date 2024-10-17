#!/bin/bash
WEBSERVER_DOMAIN="$1"

wp core install --url="$(echo $WEBSERVER_DOMAIN)" --title="Gato GraphQL" --admin_user=admin --admin_password=admin --admin_email=admin@example.com 
wp option update siteurl "https://$(echo $WEBSERVER_DOMAIN)" 
wp option update home "https://$(echo $WEBSERVER_DOMAIN)" 
# This change is needed on InstaWP
# wp option update admin_email "admin@example.com"

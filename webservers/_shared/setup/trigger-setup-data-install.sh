#!/bin/bash
# Call any WordPress page on the site, to trigger the "Install Setup Data" process
# and generate the entities
WEBSERVER_DOMAIN="$1"
curl -I "https://$WEBSERVER_DOMAIN/wp-admin/"

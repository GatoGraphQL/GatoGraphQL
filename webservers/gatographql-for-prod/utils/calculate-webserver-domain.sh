#!/bin/bash
########################################################################
# Inputs
# ----------------------------------------------------------------------
LOCALHOST_WEBSERVER_DOMAIN="$1"

# ----------------------------------------------------------------------
# Check if the Proxy is enabled in Lando's config.
#
# The webserver's domain is:
# 
# - The proxy domain, if enabled
# - The localhost (with a randomly-assigned port), otherwise
#
# To disable the proxy, add in ~/.lando/config.yml:
#
#    proxy: 'OFF'
#
# @see https://docs.lando.dev/core/v3/proxy.html
# ----------------------------------------------------------------------
MAYBE_ENABLED_PROXIED_WEBSERVER_DOMAIN=$(echo $LANDO_INFO | grep -E -o "https:\/\/$PROXIED_WEBSERVER_DOMAIN" | grep -E -o "$PROXIED_WEBSERVER_DOMAIN")
WEBSERVER_DOMAIN=(${MAYBE_ENABLED_PROXIED_WEBSERVER_DOMAIN:=$LOCALHOST_WEBSERVER_DOMAIN})

echo $WEBSERVER_DOMAIN

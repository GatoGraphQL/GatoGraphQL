#!/bin/bash
# Was a fix for https://github.com/lando/lando/issues/2913, where cURL could
# not reach wordpress.org over HTTPS. Lando's images carry the certificates
# now, so the plugin is left installed but NOT activated: it is from 2018 and
# calls define() with the case-insensitive third argument PHP removed in 8.0,
# which prints a warning over every page of a site running PHP 8.1.
#
# Activate it by hand if an environment turns out to still need it.
FILE=/tmp/safly-curl-patch.1.0.0.zip
if [ ! -f "$FILE" ]; then
    echo "Downloading SaFly Curl Patch"
    cd /tmp
    curl -O https://downloads.wordpress.org/plugin/safly-curl-patch.1.0.0.zip
    unzip safly-curl-patch.1.0.0.zip
else
    echo "SaFly Curl Patch already exists"
fi

DIR=/app/wordpress/wp-content/plugins/safly-curl-patch
if [ ! -d "$DIR" ]; then
    echo "Installing SaFly Curl Patch in WordPress"
    mv safly-curl-patch /app/wordpress/wp-content/plugins/
fi
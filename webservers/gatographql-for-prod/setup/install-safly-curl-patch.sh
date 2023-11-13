#!/bin/sh
#Fix for https://github.com/lando/lando/issues/2913
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
    cd /app/wordpress
    wp plugin activate safly-curl-patch
fi
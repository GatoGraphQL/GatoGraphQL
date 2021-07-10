#!/bin/sh
#Fix for https://github.com/lando/lando/issues/2913
echo "Downloading and installing SaFly Curl Patch"
cd /tmp
curl -O https://downloads.wordpress.org/plugin/safly-curl-patch.1.0.0.zip
unzip safly-curl-patch.1.0.0.zip
mv safly-curl-patch /app/wordpress/wp-content/plugins/
wp plugin activate safly-curl-patch --path=/app/wordpress
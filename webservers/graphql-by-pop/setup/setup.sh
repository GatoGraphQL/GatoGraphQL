#!/bin/sh
wp config create --dbname=wordpress --dbuser=wordpress --dbpass=wordpress --dbhost=database --skip-check --force --path=/app/wordpress
if wp core is-installed --path=/app/wordpress; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
/bash/sh ./install.sh
/bash/sh ./configure.sh
/bash/sh ./load-custom-code.sh
/bash/sh ./activate-plugins.sh

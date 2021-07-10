#!/bin/sh
wp config create --dbname=wordpress --dbuser=wordpress --dbpass=wordpress --dbhost=database --skip-check --force --path=/app/wordpress
if wp core is-installed --path=/app/wordpress; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
./install.sh
./configure.sh
./load-custom-code.sh
./activate-plugins.sh

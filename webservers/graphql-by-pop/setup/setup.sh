#!/bin/sh
wp config create --dbname=wordpress --dbuser=wordpress --dbpass=wordpress --dbhost=database --skip-check --force --path=/app/wordpress
if wp core is-installed --path=/app/wordpress; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
# Install
wp core install --url="$SITE_URL" --title="$SITE_TITLE" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --path=/app/wordpress 
# Configure
wp config set WP_DEBUG true --raw --path=/app/wordpress
wp rewrite structure '/%postname%/' --hard --path=/app/wordpress
cp ../assets/.htaccess ../wordpress
# Load custom code
sed "s#require_once ABSPATH . 'wp-settings.php';#require_once(__DIR__ . '/../vendor/autoload.php'); require_once(__DIR__ . '/../code-src/initialize-pop-components.php'); require_once  ABSPATH  .  'wp-settings.php' ;#g" ../wordpress/wp-config.php > ../wp-config.php.tmp
mv ../wp-config.php.tmp ../wordpress/wp-config.php
# Activate own plugins
wp plugin activate engine-wp-bootloader --path=/app/wordpress

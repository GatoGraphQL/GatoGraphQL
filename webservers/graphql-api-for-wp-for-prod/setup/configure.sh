#!/bin/sh
wp config set WP_DEBUG true --raw --path=/app/wordpress
wp config set WP_DEBUG_DISPLAY false --raw --path=/app/wordpress
wp config set GRAPHQL_API_ENABLE_UNSAFE_DEFAULTS true --raw --path=/app/wordpress
wp rewrite structure '/%postname%/' --hard --path=/app/wordpress
cp /app/assets/.htaccess /app/wordpress
cp /app/assets/phpinfo.php /app/wordpress
cp /app/assets/favicon.ico /app/wordpress

#!/bin/sh
wp config set WP_DEBUG true --raw --path=/app/wordpress
wp config set WP_DEBUG_DISPLAY false --raw --path=/app/wordpress
# wp config set GATO_GRAPHQL_SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR false --raw --path=/app/wordpress
wp rewrite structure '/%postname%/' --hard --path=/app/wordpress
cp /app/assets/.htaccess /app/wordpress
cp /app/assets/phpinfo.php /app/wordpress
cp /app/assets/favicon.ico /app/wordpress

#!/bin/sh
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_DISPLAY false --raw
# wp config set GATOGRAPHQL_SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR false --raw
wp rewrite structure '/%postname%/' --hard
cp /app/assets/.htaccess /app/wordpress
cp /app/assets/phpinfo.php /app/wordpress
cp /app/assets/favicon.ico /app/wordpress

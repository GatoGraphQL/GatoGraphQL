#!/bin/bash
wp config set WP_DEBUG true --raw
# wp config set WP_DEBUG_DISPLAY false --raw
wp rewrite structure '/%postname%/' --hard
wp option update uploads_use_yearmonth_folders '0'
wp option update admin_email wordpress@example.com
cp /app/assets/.htaccess /app/wordpress
cp /app/assets/phpinfo.php /app/wordpress
cp /app/assets/favicon.ico /app/wordpress
# Test creating media items via URL from files without extension
cp /app/wordpress/license.txt /app/wordpress/license

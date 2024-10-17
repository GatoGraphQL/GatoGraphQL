#!/bin/bash
# wp config set GATOGRAPHQL_SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR false --raw
wp rewrite structure '/%postname%/' --hard
wp option update uploads_use_yearmonth_folders '0'
wp option update admin_email wordpress@example.com
cp /app/_shared-webserver/assets/.htaccess /app/wordpress
cp /app/_shared-webserver/assets/phpinfo.php /app/wordpress
cp /app/_shared-webserver/assets/favicon.ico /app/wordpress
# Test creating media items via URL from files without extension
cp /app/wordpress/license.txt /app/wordpress/license

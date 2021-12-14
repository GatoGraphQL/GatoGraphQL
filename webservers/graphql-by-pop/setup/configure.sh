#!/bin/sh
wp config set WP_DEBUG true --raw --path=/app/wordpress
wp rewrite structure '/%postname%/' --hard --path=/app/wordpress
cp /app/assets/.htaccess /app/wordpress
cp /app/assets/phpinfo.php /app/wordpress

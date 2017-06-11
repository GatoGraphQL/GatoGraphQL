rm $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/templates/*.tmpl.js
rm $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/php-templates/compiled/*.php
cd $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/templates/

handlebars elements/wsl-networklinks.tmpl -m --extension=tmpl -f ../dist/templates/wsl-networklinks.tmpl.js
php -f $POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/compile.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helpers.handlebars.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helper-functions.php require[]=$POP_APP_PATH/wp-content/plugins/pop-coreprocessors/php-templates/cli/helpers.handlebars.php require[]=$POP_APP_PATH/wp-content/plugins/pop-coreprocessors/php-templates/cli/helper-functions.php input=elements/wsl-networklinks.tmpl output=../../php-templates/compiled/wsl-networklinks.php

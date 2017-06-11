rm $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/js/dist/templates/*.tmpl.js
rm $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/php-templates/compiled/*.php
cd $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/js/templates/

handlebars blocks/block.tmpl -m --extension=tmpl -f ../dist/templates/block.tmpl.js
php -f $POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/compile.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helpers.handlebars.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helper-functions.php input=blocks/block.tmpl output=../../php-templates/compiled/block.php

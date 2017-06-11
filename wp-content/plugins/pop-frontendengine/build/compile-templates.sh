rm $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/templates/*.tmpl.js
rm $POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/compiled/*.php
cd $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/templates/

handlebars pagesections/pagesectionextension-frame.tmpl -m --extension=tmpl -f ../dist/templates/pagesectionextension-frame.tmpl.js
php -f $POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/compile.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helpers.handlebars.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helper-functions.php input=pagesections/pagesectionextension-frame.tmpl output=../../php-templates/compiled/pagesectionextension-frame.php
handlebars pagesections/pagesectionextension-replicable.tmpl -m --extension=tmpl -f ../dist/templates/pagesectionextension-replicable.tmpl.js
php -f $POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/compile.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helpers.handlebars.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helper-functions.php input=pagesections/pagesectionextension-replicable.tmpl output=../../php-templates/compiled/pagesectionextension-replicable.php
handlebars structures/extension-appendableclass.tmpl -m --extension=tmpl -f ../dist/templates/extension-appendableclass.tmpl.js
php -f $POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/compile.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helpers.handlebars.php require[]=$POP_APP_PATH/wp-content/plugins/pop-frontendengine/php-templates/cli/helper-functions.php input=structures/extension-appendableclass.tmpl output=../../php-templates/compiled/extension-appendableclass.php

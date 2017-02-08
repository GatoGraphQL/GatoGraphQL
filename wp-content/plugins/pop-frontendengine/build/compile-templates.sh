rm $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/templates/

handlebars pagesections/pagesectionextension-frame.tmpl -m --extension=tmpl -f ../dist/templates/pagesectionextension-frame.tmpl.js
handlebars pagesections/pagesectionextension-replicable.tmpl -m --extension=tmpl -f ../dist/templates/pagesectionextension-replicable.tmpl.js

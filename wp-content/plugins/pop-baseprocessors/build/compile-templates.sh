rm $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/js/templates/

handlebars blocks/block.tmpl -m --extension=tmpl -f ../dist/templates/block.tmpl.js

rm $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/js/templates/

handlebars layouts/layout-stance.tmpl -m --extension=tmpl -f ../dist/templates/layout-stance.tmpl.js

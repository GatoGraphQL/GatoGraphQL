rm $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/templates/

handlebars elements/wsl-networklinks.tmpl -m --extension=tmpl -f ../dist/templates/wsl-networklinks.tmpl.js
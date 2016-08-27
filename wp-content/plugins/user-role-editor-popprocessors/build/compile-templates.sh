rm $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/js/templates/

handlebars layouts/typeahead/ure-layoutuser-typeahead-selected-filterbycommunity.tmpl -m --extension=tmpl -f ../dist/templates/ure-layoutuser-typeahead-selected-filterbycommunity.tmpl.js
handlebars layouts/ure-layoutuser-memberprivileges.tmpl -m --extension=tmpl -f ../dist/templates/ure-layoutuser-memberprivileges.tmpl.js
handlebars layouts/ure-layoutuser-memberstatus.tmpl -m --extension=tmpl -f ../dist/templates/ure-layoutuser-memberstatus.tmpl.js
handlebars layouts/ure-layoutuser-membertags.tmpl -m --extension=tmpl -f ../dist/templates/ure-layoutuser-membertags.tmpl.js
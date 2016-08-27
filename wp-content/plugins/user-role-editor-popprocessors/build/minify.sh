
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/user-role-editor-popprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/user-role-editor-popprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/js/dist/user-role-editor-popprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/user-role-editor-popprocessors/js/templates&f=ure-layoutuser-typeahead-selected-filterbycommunity.tmpl.js,ure-layoutuser-memberprivileges.tmpl.js,ure-layoutuser-memberstatus.tmpl.js,ure-layoutuser-membertags.tmpl.js"


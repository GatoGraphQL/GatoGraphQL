
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-baseprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-baseprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/js/dist/pop-baseprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-baseprocessors/js/templates&f=block.tmpl.js"

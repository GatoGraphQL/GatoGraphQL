
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-useravatar/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-useravatar/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/pop-useravatar.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-useravatar/js/templates&f=formcomponent-fileupload-picture.tmpl.js,fileupload-picture-upload.tmpl.js,fileupload-picture-download.tmpl.js"


###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-useravatar/js/libraries/*.js
cd $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/
cp libraries/fileupload.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-useravatar/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/pop-useravatar.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-useravatar/js/libraries&f=fileupload.js"


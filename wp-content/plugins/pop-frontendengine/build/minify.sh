
###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/*.js
cd $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/
cp helpers.handlebars.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
cp history.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
cp interceptors.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
cp jslibrary-manager.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
cp jsruntime-manager.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
cp pagesection-manager.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
cp pop-manager.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
cp utils.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/popfrontend.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries&f=helpers.handlebars.js,utils.js,jslibrary-manager.js,jsruntime-manager.js,pagesection-manager.js,history.js,interceptors.js,pop-manager.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/popfrontend.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/popfrontend.bundle.min.js -c warnings=false -m


###########################
# JS LIBRARIES
###########################

cd $POP_APP_PATH/wp-content/plugins/public-post-preview-pop/js/
uglifyjs libraries/ppp-functions.js -o dist/libraries/ppp-functions.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/public-post-preview-pop/js/libraries/*.js
cp dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/public-post-preview-pop/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/public-post-preview-pop/js/dist/bundles/public-post-preview-pop.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/public-post-preview-pop/js/libraries&f=ppp-functions.min.js"

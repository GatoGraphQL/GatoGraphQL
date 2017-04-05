
# ###########################
# # JS LIBRARIES
# ###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries/*.js
cd $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/
cp libraries/cdn.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries/
cp libraries/cdn-thumbprints.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries/

cd $POP_APP_PATH/wp-content/plugins/pop-cdn-core/plugins/pop-coreprocessors/js/
cp libraries/cdn-hooks.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries/cdn-core-hooks.js

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/dist/pop-cdn-core.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries&f=cdn.js,cdn-thumbprints.js,cdn-core-hooks.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/dist/pop-cdn-core.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/dist/pop-cdn-core.bundle.min.js -c warnings=false -m
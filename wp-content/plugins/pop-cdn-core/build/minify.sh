
# ###########################
# # JS LIBRARIES
# ###########################

cd $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/
uglifyjs libraries/cdn.js -o dist/libraries/cdn.min.js -c warnings=false -m
uglifyjs libraries/cdn-thumbprints.js -o dist/libraries/cdn-thumbprints.min.js -c warnings=false -m
uglifyjs libraries/plugins/pop-coreprocessors/cdn-hooks.js -o dist/libraries/plugins/pop-coreprocessors/cdn-hooks.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/dist/libraries/plugins/pop-coreprocessors/cdn-hooks.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries/cdn-coreprocessors-hooks.min.js
wget -O $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/dist/bundles/pop-cdn-core.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-cdn-core/js/libraries&f=cdn.min.js,cdn-thumbprints.min.js,cdn-coreprocessors-hooks.min.js"


# ###########################
# # JS LIBRARIES
# ###########################

cd $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/
uglifyjs libraries/sw.js -o dist/libraries/sw.min.js -c warnings=false -m
uglifyjs libraries/sw-initial.js -o dist/libraries/sw-initial.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-serviceworkers/js/libraries/*.js
cp dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-serviceworkers/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/dist/bundles/pop-serviceworkers.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-serviceworkers/js/libraries&f=sw.min.js,sw-initial.min.js"


# ###########################
# # JS LIBRARIES
# ###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-serviceworkers/js/libraries/*.js
cd $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/
cp libraries/sw.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-serviceworkers/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/dist/pop-serviceworkers.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-serviceworkers/js/libraries&f=sw.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/dist/pop-serviceworkers.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/dist/pop-serviceworkers.bundle.min.js -c warnings=false -m
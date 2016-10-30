
###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-prettyprint/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/pop-prettyprint/js/libraries/pop-prettyprint.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-prettyprint/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-prettyprint/js/dist/pop-prettyprint.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-prettyprint/js/libraries&f=pop-prettyprint.js"
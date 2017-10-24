
###########################
# JS LIBRARIES
###########################

cd $POP_APP_PATH/wp-content/plugins/google-analytics-dashboard-for-wp-pop/js/
uglifyjs libraries/gadwp-functions.js -o dist/libraries/gadwp-functions.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/google-analytics-dashboard-for-wp-pop/js/libraries/*.js
cp dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/google-analytics-dashboard-for-wp-pop/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/google-analytics-dashboard-for-wp-pop/js/dist/bundles/google-analytics-dashboard-for-wp-pop.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/google-analytics-dashboard-for-wp-pop/js/libraries&f=gadwp-functions.min.js"

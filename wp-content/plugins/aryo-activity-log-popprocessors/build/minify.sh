
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/bundles/aryo-activity-log-popprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/templates&f=aal-layout-previewnotification.tmpl.js,aal-layout-notificationtime.tmpl.js,aal-layout-notificationicon.tmpl.js"

###########################
# JS LIBRARIES
###########################

cd $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/
uglifyjs libraries/notifications.js -o dist/libraries/notifications.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/bundles/aryo-activity-log-popprocessors.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/libraries&f=notifications.min.js"


###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/templates&f=aal-layout-previewnotification.tmpl.js,aal-layout-notificationtime.tmpl.js,aal-layout-notificationicon.tmpl.js"

###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/libraries/notifications.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/aryo-activity-log-popprocessors/js/libraries&f=notifications.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.bundle.min.js -c warnings=false -m
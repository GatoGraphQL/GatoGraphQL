
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/wordpress-social-login-popprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/wordpress-social-login-popprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/wordpress-social-login-popprocessors/js/templates&f=wsl-networklinks.tmpl.js"

###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/wordpress-social-login-popprocessors/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/libraries/wsl-functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/wordpress-social-login-popprocessors/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/wordpress-social-login-popprocessors/js/libraries&f=wsl-functions.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.bundle.min.js -c warnings=false -m
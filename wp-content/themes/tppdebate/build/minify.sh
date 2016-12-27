
###########################
# STYLES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/tppdebate/css/*.css
cp $POP_APP_PATH/wp-content/themes/tppdebate/css/custom.bootstrap.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/tppdebate/css/
cp $POP_APP_PATH/wp-content/themes/tppdebate/css/typeahead.js-bootstrap.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/tppdebate/css/
cp $POP_APP_PATH/wp-content/themes/tppdebate/css/style.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/tppdebate/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/tppdebate/css/dist/tppdebate.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/themes/tppdebate/css&f=custom.bootstrap.css,typeahead.js-bootstrap.css,style.css"


#################################################################################
# COMBINED APP: Pack all dependencies together into one single file
#################################################################################


###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/*.*
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
cp $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/pop-useravatar.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/events-manager-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
cp $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/js/dist/user-role-editor-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/poptheme-wassup.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/js/dist/poptheme-wassup-votingprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates/
wget -O $POP_APP_PATH/wp-content/themes/tppdebate/js/dist/tppdebate-app.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/tppdebate/js/templates&f=pop-coreprocessors.templates.bundle.min.js,aryo-activity-log-popprocessors.templates.bundle.min.js,pop-useravatar.templates.bundle.min.js,events-manager-popprocessors.templates.bundle.min.js,user-role-editor-popprocessors.templates.bundle.min.js,wordpress-social-login-popprocessors.templates.bundle.min.js,poptheme-wassup.templates.bundle.min.js,poptheme-wassup-votingprocessors.templates.bundle.min.js"


###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/popfrontend.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/dist/pop-serviceworkers.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/pop-useravatar.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/events-manager-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/photoswipe-pop.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/poptheme-wassup.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/tppdebate/js/dist/tppdebate-app.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/tppdebate/js/libraries&f=popfrontend.bundle.min.js,pop-coreprocessors.bundle.min.js,pop-serviceworkers.bundle.min.js,aryo-activity-log-popprocessors.bundle.min.js,pop-useravatar.bundle.min.js,events-manager-popprocessors.bundle.min.js,wordpress-social-login-popprocessors.bundle.min.js,photoswipe-pop.bundle.min.js,poptheme-wassup.bundle.min.js"


###########################
# STYLES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/css/*.css
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/css/dist/pop-coreprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/dist/poptheme-wassup.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/css/dist/poptheme-wassup-sectionprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/css/dist/poptheme-wassup-votingprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/css/
cp $POP_APP_PATH/wp-content/themes/tppdebate/css/dist/tppdebate.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/tppdebate/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/tppdebate/css/dist/tppdebate-app.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/tppdebate/css&f=pop-coreprocessors.bundle.min.css,poptheme-wassup.bundle.min.css,poptheme-wassup-sectionprocessors.bundle.min.css,poptheme-wassup-votingprocessors.bundle.min.css,tppdebate.bundle.min.css"



###########################
# STYLES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/agendaurbana/css/*.css
cp $POP_APP_PATH/wp-content/themes/agendaurbana/css/custom.bootstrap.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/agendaurbana/css/
cp $POP_APP_PATH/wp-content/themes/agendaurbana/css/typeahead.js-bootstrap.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/agendaurbana/css/
cp $POP_APP_PATH/wp-content/themes/agendaurbana/css/style.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/agendaurbana/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/agendaurbana/css/dist/agendaurbana.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/themes/agendaurbana/css&f=custom.bootstrap.css,typeahead.js-bootstrap.css,style.css"


#################################################################################
# COMBINED APP: Pack all dependencies together into one single file
#################################################################################


###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/*.*
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/
cp $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/pop-useravatar.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/events-manager-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/
cp $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/js/dist/user-role-editor-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/poptheme-wassup.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates/
wget -O $POP_APP_PATH/wp-content/themes/agendaurbana/js/dist/agendaurbana-app.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/agendaurbana/js/templates&f=pop-coreprocessors.templates.bundle.min.js,aryo-activity-log-popprocessors.templates.bundle.min.js,pop-useravatar.templates.bundle.min.js,events-manager-popprocessors.templates.bundle.min.js,user-role-editor-popprocessors.templates.bundle.min.js,wordpress-social-login-popprocessors.templates.bundle.min.js,poptheme-wassup.templates.bundle.min.js"


###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/popfrontend.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/aryo-activity-log-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/pop-useravatar.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/events-manager-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/wordpress-social-login-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/photoswipe-pop.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/poptheme-wassup.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/agendaurbana/js/dist/agendaurbana-app.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/agendaurbana/js/libraries&f=popfrontend.bundle.min.js,pop-coreprocessors.bundle.min.js,aryo-activity-log-popprocessors.bundle.min.js,pop-useravatar.bundle.min.js,events-manager-popprocessors.bundle.min.js,wordpress-social-login-popprocessors.bundle.min.js,photoswipe-pop.bundle.min.js,poptheme-wassup.bundle.min.js"


###########################
# STYLES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/css/*.css
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/css/dist/pop-coreprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/dist/poptheme-wassup.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/css/dist/poptheme-wassup-sectionprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/css/
cp $POP_APP_PATH/wp-content/themes/agendaurbana/css/dist/agendaurbana.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/agendaurbana/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/agendaurbana/css/dist/agendaurbana-app.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/agendaurbana/css&f=pop-coreprocessors.bundle.min.css,poptheme-wassup.bundle.min.css,poptheme-wassup-sectionprocessors.bundle.min.css,agendaurbana.bundle.min.css"


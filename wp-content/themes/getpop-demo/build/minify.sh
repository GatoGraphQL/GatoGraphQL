
###########################
# STYLES
###########################

cd $POP_APP_PATH/wp-content/themes/getpop-demo/css/
uglifycss libraries/custom.bootstrap.css --output dist/libraries/custom.bootstrap.min.css
uglifycss libraries/typeahead.js-bootstrap.css --output dist/libraries/typeahead.js-bootstrap.min.css
uglifycss libraries/style.css --output dist/libraries/style.min.css

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/getpop-demo/css/*.css
cp dist/libraries/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/themes/getpop-demo/css/
wget -O $POP_APP_PATH/wp-content/themes/getpop-demo/css/dist/bundles/getpop-demo.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/themes/getpop-demo/css&f=custom.bootstrap.min.css,typeahead.js-bootstrap.min.css,style.min.css"



#################################################################################
# COMBINED APP: Pack all dependencies together into one single file
#################################################################################


###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/*.*
cp $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/bundles/pop-frontendengine.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/pop-baseprocessors/js/dist/bundles/pop-baseprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/bundles/pop-bootstrapprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/bundles/pop-coreprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/bundles/aryo-activity-log-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/bundles/pop-useravatar.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/bundles/events-manager-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/js/dist/bundles/user-role-editor-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/bundles/wordpress-social-login-popprocessors.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/bundles/poptheme-wassup.templates.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates/
wget -O $POP_APP_PATH/wp-content/themes/getpop-demo/js/dist/bundles/getpop-demo-app.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/getpop-demo/js/templates&f=pop-frontendengine.templates.bundle.min.js,pop-baseprocessors.templates.bundle.min.js,pop-bootstrapprocessors.templates.bundle.min.js,pop-coreprocessors.templates.bundle.min.js,aryo-activity-log-popprocessors.templates.bundle.min.js,pop-useravatar.templates.bundle.min.js,events-manager-popprocessors.templates.bundle.min.js,user-role-editor-popprocessors.templates.bundle.min.js,wordpress-social-login-popprocessors.templates.bundle.min.js,poptheme-wassup.templates.bundle.min.js"


###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/bundles/pop-frontendengine.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/bundles/pop-bootstrapprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/bundles/pop-coreprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-cdn-core/js/dist/bundles/pop-cdn-core.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-resourceloader/js/dist/bundles/pop-resourceloader.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/js/dist/bundles/pop-serviceworkers.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/bundles/aryo-activity-log-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/bundles/pop-useravatar.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/bundles/events-manager-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/js/dist/bundles/wordpress-social-login-popprocessors.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/public-post-preview-pop/js/dist/bundles/public-post-preview-pop.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/bundles/photoswipe-pop.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/bundles/poptheme-wassup.bundle.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/getpop-demo/js/dist/bundles/getpop-demo-app.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/getpop-demo/js/libraries&f=pop-frontendengine.bundle.min.js,pop-bootstrapprocessors.bundle.min.js,pop-coreprocessors.bundle.min.js,pop-cdn-core.bundle.min.js,pop-resourceloader.bundle.min.js,pop-serviceworkers.bundle.min.js,aryo-activity-log-popprocessors.bundle.min.js,pop-useravatar.bundle.min.js,events-manager-popprocessors.bundle.min.js,wordpress-social-login-popprocessors.bundle.min.js,public-post-preview-pop.bundle.min.js,photoswipe-pop.bundle.min.js,poptheme-wassup.bundle.min.js"


###########################
# STYLES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/css/*.css
# cp $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/css/dist/bundles/pop-bootstrapprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/css/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/css/dist/bundles/pop-coreprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/dist/bundles/poptheme-wassup.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/css/dist/bundles/poptheme-wassup-sectionprocessors.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/css/
cp $POP_APP_PATH/wp-content/themes/getpop-demo/css/dist/bundles/getpop-demo.bundle.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/apps/getpop-demo/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/themes/getpop-demo/css/dist/bundles/getpop-demo-app.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/apps/getpop-demo/css&f=pop-coreprocessors.bundle.min.css,poptheme-wassup.bundle.min.css,poptheme-wassup-sectionprocessors.bundle.min.css,getpop-demo.bundle.min.css"



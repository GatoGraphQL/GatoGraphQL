
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/pop-bootstrapprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/templates&f=blockgroup-carousel.tmpl.js,blockgroup-collapsepanelgroup.tmpl.js,blockgroup-tabpanel.tmpl.js,blockgroup-viewcomponent.tmpl.js,pagesection-pagetab.tmpl.js,pagesection-tabpane.tmpl.js,pagesection-modal.tmpl.js"

###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/*.js
cd $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/
cp libraries/bootstrap.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
cp libraries/custombootstrap.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/pop-bootstrapprocessors.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries&f=custombootstrap.js,bootstrap.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/pop-bootstrapprocessors.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/pop-bootstrapprocessors.bundle.min.js -c warnings=false -m


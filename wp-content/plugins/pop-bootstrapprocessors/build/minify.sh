
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
# cp libraries/helpers.handlebars.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/3rdparties/analytics.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/3rdparties/daterange.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/3rdparties/dynamicmaxheight.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/3rdparties/multiselect.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/3rdparties/perfectscrollbar.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/3rdparties/typeahead.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/3rdparties/waypoints.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/addeditpost.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/block-functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/bootstrap-carousel.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
cp libraries/bootstrap.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/controls.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
cp libraries/custombootstrap.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/editor.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/mentions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/featuredimage.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/modals.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/system.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/user-account.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp libraries/window.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/

echo ##########################
echo INCLUDED 3rd PARTY LIBRARY JS FILES
echo ##########################
# cp $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/includes/jquery.fullscreen-min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/includes/bootstrap-multiselect.0.9.13.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/
# cp $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/includes/jquery.dynamicmaxheight.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/pop-bootstrapprocessors.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-bootstrapprocessors/js/libraries&f=custombootstrap.js,bootstrap.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/pop-bootstrapprocessors.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/pop-bootstrapprocessors.bundle.min.js -c warnings=false -m


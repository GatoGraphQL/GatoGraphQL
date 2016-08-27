
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/events-manager-popprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/templates&f=em-frame-createlocationmap.tmpl.js,em-layout-carousel-indicators-eventdate.tmpl.js,em-layoutcalendar-content.tmpl.js,em-layoutlocation-typeahead-component.tmpl.js,em-layoutlocation-typeahead-selected.tmpl.js,em-layout-datetime.tmpl.js,em-layout-locationaddress.tmpl.js,em-layout-locationname.tmpl.js,em-layout-locations.tmpl.js,em-script-triggertypeaheadselect-location.tmpl.js,em-layoutevent-tablecol.tmpl.js,em-calendar-inner.tmpl.js,em-calendar.tmpl.js,em-map-addmarker.tmpl.js,em-map-div.tmpl.js,em-map-individual.tmpl.js,em-map-inner.tmpl.js,em-map-script-drawmarkers.tmpl.js,em-map-script-markers.tmpl.js,em-map-script-resetmarkers.tmpl.js,em-map-script.tmpl.js,em-map.tmpl.js,em-post-map-scriptcustomization.tmpl.js,em-user-map-scriptcustomization.tmpl.js,em-formcomponent-typeaheadmap.tmpl.js,em-viewcomponent-locationbutton.tmpl.js,em-viewcomponent-locationbuttoninner.tmpl.js"

###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/libraries/create-location.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/libraries/map.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/libraries/typeahead-map.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/libraries/map-collection.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/libraries/3rdparties/fullcalendar.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/events-manager-popprocessors.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries&f=create-location.js,map.js,typeahead-map.js,map-collection.js,fullcalendar.js"
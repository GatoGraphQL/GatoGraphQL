
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/bundles/events-manager-popprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/templates&f=em-frame-createlocationmap.tmpl.js,em-layout-carousel-indicators-eventdate.tmpl.js,em-layoutcalendar-content.tmpl.js,em-layoutlocation-typeahead-component.tmpl.js,em-layoutlocation-typeahead-selected.tmpl.js,em-layout-datetime.tmpl.js,em-layout-locationaddress.tmpl.js,em-layout-locationname.tmpl.js,em-layout-locations.tmpl.js,em-script-triggertypeaheadselect-location.tmpl.js,em-layoutevent-tablecol.tmpl.js,em-calendar-inner.tmpl.js,em-calendar.tmpl.js,em-map-addmarker.tmpl.js,em-map-div.tmpl.js,em-map-individual.tmpl.js,em-map-inner.tmpl.js,em-map-script-drawmarkers.tmpl.js,em-map-script-markers.tmpl.js,em-map-script-resetmarkers.tmpl.js,em-map-script.tmpl.js,em-map.tmpl.js,em-post-map-scriptcustomization.tmpl.js,em-user-map-scriptcustomization.tmpl.js,em-map-staticimage.tmpl.js,em-map-staticimage-urlparam.tmpl.js,em-map-staticimage-locations.tmpl.js,em-formcomponent-typeaheadmap.tmpl.js,em-viewcomponent-locationbutton.tmpl.js,em-viewcomponent-locationbuttoninner.tmpl.js"

###########################
# JS LIBRARIES
###########################

cd $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/
uglifyjs libraries/helpers.handlebars.js -o dist/libraries/helpers.handlebars.min.js -c warnings=false -m
uglifyjs libraries/create-location.js -o dist/libraries/create-location.min.js -c warnings=false -m
uglifyjs libraries/typeahead-map.js -o dist/libraries/typeahead-map.min.js -c warnings=false -m
uglifyjs libraries/map-collection.js -o dist/libraries/map-collection.min.js -c warnings=false -m
uglifyjs libraries/map/map.js -o dist/libraries/map/map.min.js -c warnings=false -m
uglifyjs libraries/map/map-memory.js -o dist/libraries/map/map-memory.min.js -c warnings=false -m
uglifyjs libraries/map/map-initmarker.js -o dist/libraries/map/map-initmarker.min.js -c warnings=false -m
uglifyjs libraries/map/map-runtime.js -o dist/libraries/map/map-runtime.min.js -c warnings=false -m
uglifyjs libraries/map/map-runtime-memory.js -o dist/libraries/map/map-runtime-memory.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/calendar/fullcalendar.js -o dist/libraries/3rdparties/calendar/fullcalendar.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/calendar/fullcalendar-memory.js -o dist/libraries/3rdparties/calendar/fullcalendar-memory.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/calendar/fullcalendar-addevents.js -o dist/libraries/3rdparties/calendar/fullcalendar-addevents.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/libraries/map/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/libraries/3rdparties/calendar/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/bundles/events-manager-popprocessors.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/events-manager-popprocessors/js/libraries&f=helpers.handlebars.min.js,create-location.min.js,map.min.js,map-memory.min.js,map-initmarker.min.js,map-runtime.min.js,map-runtime-memory.min.js,typeahead-map.min.js,map-collection.min.js,fullcalendar.min.js,fullcalendar-memory.min.js,fullcalendar-addevents.min.js"

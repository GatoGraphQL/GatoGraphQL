rm $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/js/templates/

handlebars formcomponents-inputs/em-formcomponent-typeaheadmap.tmpl -m --extension=tmpl -f ../dist/templates/em-formcomponent-typeaheadmap.tmpl.js
handlebars layouts/em-layout-datetime.tmpl -m --extension=tmpl -f ../dist/templates/em-layout-datetime.tmpl.js
handlebars maps/em-map-individual.tmpl -m --extension=tmpl -f ../dist/templates/em-map-individual.tmpl.js
handlebars maps/em-map-script.tmpl -m --extension=tmpl -f ../dist/templates/em-map-script.tmpl.js
handlebars maps/em-post-map-scriptcustomization.tmpl -m --extension=tmpl -f ../dist/templates/em-post-map-scriptcustomization.tmpl.js
handlebars maps/em-user-map-scriptcustomization.tmpl -m --extension=tmpl -f ../dist/templates/em-user-map-scriptcustomization.tmpl.js
handlebars maps/em-map-addmarker.tmpl -m --extension=tmpl -f ../dist/templates/em-map-addmarker.tmpl.js
handlebars maps/em-map-div.tmpl -m --extension=tmpl -f ../dist/templates/em-map-div.tmpl.js
handlebars maps/em-map-script-drawmarkers.tmpl -m --extension=tmpl -f ../dist/templates/em-map-script-drawmarkers.tmpl.js
handlebars maps/em-map-script-markers.tmpl -m --extension=tmpl -f ../dist/templates/em-map-script-markers.tmpl.js
handlebars maps/em-map-script-resetmarkers.tmpl -m --extension=tmpl -f ../dist/templates/em-map-script-resetmarkers.tmpl.js
handlebars frames/em-frame-createlocationmap.tmpl -m --extension=tmpl -f ../dist/templates/em-frame-createlocationmap.tmpl.js
handlebars layouts/em-layout-carousel-indicators-eventdate.tmpl -m --extension=tmpl -f ../dist/templates/em-layout-carousel-indicators-eventdate.tmpl.js
handlebars layouts/em-layout-locationaddress.tmpl -m --extension=tmpl -f ../dist/templates/em-layout-locationaddress.tmpl.js
handlebars layouts/em-layout-locationname.tmpl -m --extension=tmpl -f ../dist/templates/em-layout-locationname.tmpl.js
handlebars layouts/em-layout-locations.tmpl -m --extension=tmpl -f ../dist/templates/em-layout-locations.tmpl.js
handlebars layouts/em-script-triggertypeaheadselect-location.tmpl -m --extension=tmpl -f ../dist/templates/em-script-triggertypeaheadselect-location.tmpl.js
handlebars layouts/em-layoutcalendar-content.tmpl -m --extension=tmpl -f ../dist/templates/em-layoutcalendar-content.tmpl.js
handlebars layouts/em-layoutevent-tablecol.tmpl -m --extension=tmpl -f ../dist/templates/em-layoutevent-tablecol.tmpl.js
handlebars layouts/em-layoutlocation-typeahead-component.tmpl -m --extension=tmpl -f ../dist/templates/em-layoutlocation-typeahead-component.tmpl.js
handlebars layouts/em-layoutlocation-typeahead-selected.tmpl -m --extension=tmpl -f ../dist/templates/em-layoutlocation-typeahead-selected.tmpl.js
handlebars structures/em-calendar-inner.tmpl -m --extension=tmpl -f ../dist/templates/em-calendar-inner.tmpl.js
handlebars structures/em-calendar.tmpl -m --extension=tmpl -f ../dist/templates/em-calendar.tmpl.js
handlebars structures/em-map-inner.tmpl -m --extension=tmpl -f ../dist/templates/em-map-inner.tmpl.js
handlebars structures/em-map.tmpl -m --extension=tmpl -f ../dist/templates/em-map.tmpl.js
handlebars viewcomponents/em-viewcomponent-locationbutton.tmpl -m --extension=tmpl -f ../dist/templates/em-viewcomponent-locationbutton.tmpl.js
handlebars viewcomponents/em-viewcomponent-locationbuttoninner.tmpl -m --extension=tmpl -f ../dist/templates/em-viewcomponent-locationbuttoninner.tmpl.js

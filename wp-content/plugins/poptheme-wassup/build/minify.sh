
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/bundles/poptheme-wassup.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/templates&f=pagesection-background.tmpl.js,pagesection-side.tmpl.js,pagesection-top.tmpl.js,layout-volunteertag.tmpl.js,layout-link-access.tmpl.js,pagesection-topsimple.tmpl.js,ure-layoutuser-profileindividual-details.tmpl.js,ure-layoutuser-profileorganization-details.tmpl.js,speechbubble.tmpl.js"


###########################
# JS LIBRARIES
###########################

cd $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/
uglifyjs libraries/condition-functions.js -o dist/libraries/condition-functions.min.js -c warnings=false -m
uglifyjs libraries/custom-functions.js -o dist/libraries/custom-functions.min.js -c warnings=false -m
uglifyjs libraries/custom-pagesection-manager.js -o dist/libraries/custom-pagesection-manager.min.js -c warnings=false -m
uglifyjs libraries/ure-communities.js -o dist/libraries/ure-communities.min.js -c warnings=false -m
uglifyjs libraries/ure-aal-functions.js -o dist/libraries/ure-aal-functions.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/*.js
cp dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/bundles/poptheme-wassup.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries&f=condition-functions.min.js,custom-functions.min.js,custom-pagesection-manager.min.js,ure-communities.min.js,ure-aal-functions.min.js"

###########################
# CSS
###########################

cd $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/
uglifycss libraries/style.css --output dist/libraries/style.min.css
uglifycss libraries/custom.bootstrap.css --output dist/libraries/custom.bootstrap.min.css
uglifycss libraries/pagesection-group.css --output dist/libraries/pagesection-group.min.css
uglifycss libraries/typeahead.js-bootstrap.css --output dist/libraries/typeahead.js-bootstrap.min.css
uglifycss includes/bootstrap-multiselect.0.9.13.css --output dist/includes/bootstrap-multiselect.0.9.13.min.css
uglifycss templates/blockgroup-home-welcome.css --output dist/templates/blockgroup-home-welcome.min.css
uglifycss templates/collapse-hometop.css --output dist/templates/collapse-hometop.min.css
uglifycss templates/quicklinkgroups.css --output dist/templates/quicklinkgroups.min.css
uglifycss templates/daterangepicker.css --output dist/templates/daterangepicker.min.css
uglifycss templates/skeleton-screen.css --output dist/templates/skeleton-screen.min.css
uglifycss templates/block-carousel.css --output dist/templates/block-carousel.min.css
uglifycss templates/fetchmore.css --output dist/templates/fetchmore.min.css
uglifycss templates/blockgroup-author.css --output dist/templates/blockgroup-author.min.css
uglifycss templates/blockgroup-authorsections.css --output dist/templates/blockgroup-authorsections.min.css
uglifycss templates/block.css --output dist/templates/block.min.css
uglifycss templates/functionalblock.css --output dist/templates/functionalblock.min.css
uglifycss templates/functionbutton.css --output dist/templates/functionbutton.min.css
uglifycss templates/socialmedia.css --output dist/templates/socialmedia.min.css
uglifycss templates/form-mypreferences.css --output dist/templates/form-mypreferences.min.css
uglifycss templates/block-comments.css --output dist/templates/block-comments.min.css
uglifycss templates/frame-addcomments.css --output dist/templates/frame-addcomments.min.css
uglifycss templates/side-sections-menu.css --output dist/templates/side-sections-menu.min.css
uglifycss templates/littleguy.css --output dist/templates/littleguy.min.css
uglifycss templates/speechbubble.css --output dist/templates/speechbubble.min.css
uglifycss templates/featuredimage.css --output dist/templates/featuredimage.min.css
uglifycss templates/multiselect.css --output dist/templates/multiselect.min.css
uglifycss templates/homemessage.css --output dist/templates/homemessage.min.css
uglifycss templates/smalldetails.css --output dist/templates/smalldetails.min.css
uglifycss templates/block-notifications.css --output dist/templates/block-notifications.min.css
uglifycss templates/scroll-notifications.css --output dist/templates/scroll-notifications.min.css
uglifycss templates/widget.css --output dist/templates/widget.min.css
uglifycss templates/dynamicmaxheight.css --output dist/templates/dynamicmaxheight.min.css
uglifycss templates/plugins/events-manager/calendar.css --output dist/templates/plugins/events-manager/calendar.min.css
uglifycss templates/plugins/events-manager/map.css --output dist/templates/plugins/events-manager/map.min.css
uglifycss templates/plugins/aryo-activity-log-pop/notification-layout.css --output dist/templates/plugins/aryo-activity-log-pop/notification-layout.min.css

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/*.css
cp dist/libraries/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
cp dist/includes/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
cp dist/templates/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
cp dist/templates/plugins/events-manager/calendar.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/em-calendar.min.css
cp dist/templates/plugins/events-manager/map.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/em-map.min.css
cp dist/templates/plugins/aryo-activity-log-pop/notification-layout.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/aal-notification-layout.min.css
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/dist/bundles/poptheme-wassup.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css&f=style.min.css,custom.bootstrap.min.css,pagesection-group.min.css,typeahead.js-bootstrap.min.css,bootstrap-multiselect.0.9.13.min.css,blockgroup-home-welcome.min.css,collapse-hometop.min.css,quicklinkgroups.min.css,skeleton-screen.min.css,block-carousel.min.css,fetchmore.min.css,blockgroup-author.min.css,blockgroup-authorsections.min.css,block.min.css,functionalblock.min.css,functionbutton.min.css,socialmedia.min.css,form-mypreferences.min.css,block-comments.min.css,frame-addcomments.min.css,side-sections-menu.min.css,littleguy.min.css,speechbubble.min.css,featuredimage.min.css,multiselect.min.css,homemessage.min.css,smalldetails.min.css,block-notifications.min.css,scroll-notifications.min.css,widget.min.css,dynamicmaxheight.min.css,em-calendar.min.css,em-map.min.css,aal-notification-layout.min.css"

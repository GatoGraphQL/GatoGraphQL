
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
uglifyjs libraries/custom-functions.js -o dist/libraries/custom-functions.min.js -c warnings=false -m
uglifyjs libraries/custom-pagesection-manager.js -o dist/libraries/custom-pagesection-manager.min.js -c warnings=false -m
uglifyjs libraries/ure-communities.js -o dist/libraries/ure-communities.min.js -c warnings=false -m
uglifyjs libraries/ure-aal-functions.js -o dist/libraries/ure-aal-functions.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/*.js
cp dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/bundles/poptheme-wassup.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries&f=custom-functions.min.js,custom-pagesection-manager.min.js,ure-communities.min.js,ure-aal-functions.min.js"

###########################
# CSS
###########################

cd $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/
uglifycss libraries/style.css --output dist/libraries/style.min.css
uglifycss libraries/custom.bootstrap.css --output dist/libraries/custom.bootstrap.min.css
uglifycss libraries/skeleton-screen.css --output dist/libraries/skeleton-screen.min.css
uglifycss libraries/typeahead.js-bootstrap.css --output dist/libraries/typeahead.js-bootstrap.min.css
uglifycss includes/bootstrap-multiselect.0.9.13.css --output dist/includes/bootstrap-multiselect.0.9.13.min.css

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/*.css
cp dist/libraries/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
cp dist/includes/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/dist/bundles/poptheme-wassup.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css&f=style.min.css,custom.bootstrap.min.css,skeleton-screen.min.css,typeahead.js-bootstrap.min.css,bootstrap-multiselect.0.9.13.min.css"


###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/poptheme-wassup.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/templates&f=pagesection-background.tmpl.js,pagesection-side.tmpl.js,pagesection-top.tmpl.js,layout-volunteertag.tmpl.js,layout-link-access.tmpl.js,pagesection-topsimple.tmpl.js,ure-layoutuser-profileindividual-details.tmpl.js,ure-layoutuser-profileorganization-details.tmpl.js,speechbubble.tmpl.js"


###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/libraries/custom-functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/libraries/ure-communities.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/libraries/ure-aal-functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/

echo ##########################
echo PLUGINS INCLUDED JS FILES
echo ##########################
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/plugins/wordpress-social-login/js/wsl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/plugins/jw-player-plugin-for-wordpress/js/jwp6-scripts.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/poptheme-wassup.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/js/libraries&f=custom-functions.js,ure-communities.js,ure-aal-functions.js"


###########################
# CSS
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/*.css
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/style.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/custom.bootstrap.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/typeahead.js-bootstrap.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/includes/bootstrap-multiselect.0.9.13.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup/css/dist/poptheme-wassup.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup/css&f=style.css,custom.bootstrap.css,typeahead.js-bootstrap.css,bootstrap-multiselect.0.9.13.css"

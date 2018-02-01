
###########################
# CSS
###########################

cd $POP_APP_PATH/wp-content/plugins/poptheme-wassup-categoryprocessors/css/
uglifycss libraries/category-layout.css --output dist/libraries/category-layout.min.css

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-categoryprocessors/css/*.css
cp dist/libraries/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-categoryprocessors/css/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup-categoryprocessors/css/dist/bundles/poptheme-wassup-categoryprocessors.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-categoryprocessors/css&f=category-layout.min.css"

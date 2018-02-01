
###########################
# CSS
###########################

cd $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/css/
uglifycss libraries/section-layout.css --output dist/libraries/section-layout.min.css

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-sectionprocessors/css/*.css
cp dist/libraries/*.min.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-sectionprocessors/css/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/css/dist/bundles/poptheme-wassup-sectionprocessors.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-sectionprocessors/css&f=section-layout.min.css"


###########################
# CSS
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-sectionprocessors/css/*.css
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/css/style.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-sectionprocessors/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/css/dist/poptheme-wassup-sectionprocessors.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-sectionprocessors/css&f=style.css"

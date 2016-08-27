
###########################
# CSS
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-categoryprocessors/css/*.css
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-categoryprocessors/css/style.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-categoryprocessors/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup-categoryprocessors/css/dist/poptheme-wassup-categoryprocessors.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-categoryprocessors/css&f=style.css"

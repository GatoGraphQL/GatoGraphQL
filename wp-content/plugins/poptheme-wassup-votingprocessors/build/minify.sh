
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-votingprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-votingprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/js/dist/poptheme-wassup-votingprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-votingprocessors/js/templates&f=layout-stance.tmpl.js"


###########################
# CSS
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-votingprocessors/css/*.css
cp $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/css/style.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-votingprocessors/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/css/dist/poptheme-wassup-votingprocessors.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/poptheme-wassup-votingprocessors/css&f=style.css"

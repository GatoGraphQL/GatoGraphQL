
###########################
# JS LIBRARIES
###########################

cd $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/
uglifyjs libraries/photoswipe-pop.js -o dist/libraries/photoswipe-pop.min.js -c warnings=false -m

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/photoswipe-pop/js/*.js
cp $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/photoswipe-pop/js/
wget -O $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/bundles/photoswipe-pop.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/photoswipe-pop/js&f=photoswipe-pop.min.js"

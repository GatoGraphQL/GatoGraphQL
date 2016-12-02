
###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/photoswipe-pop/js/*.js
cp $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/photoswipe-pop.js  $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/photoswipe-pop/js/
wget -O $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/photoswipe-pop.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/photoswipe-pop/js&f=photoswipe-pop.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/photoswipe-pop.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/photoswipe-pop/js/dist/photoswipe-pop.bundle.min.js -c warnings=false -m
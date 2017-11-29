
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/bundles/pop-frontendengine.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/templates&f=pagesectionextension-replicable.tmpl.js,pagesectionextension-frame.tmpl.js,extension-appendableclass.tmpl.js"

###########################
# JS LIBRARIES
###########################

cd $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/
uglifyjs libraries/helpers.handlebars.js -o dist/libraries/helpers.handlebars.min.js -c warnings=false -m
uglifyjs libraries/history.js -o dist/libraries/history.min.js -c warnings=false -m
uglifyjs libraries/interceptors.js -o dist/libraries/interceptors.min.js -c warnings=false -m
uglifyjs libraries/jslibrary-manager.js -o dist/libraries/jslibrary-manager.min.js -c warnings=false -m
uglifyjs libraries/codesplit-jslibrary-manager.js -o dist/libraries/codesplit-jslibrary-manager.min.js -c warnings=false -m
uglifyjs libraries/jsruntime-manager.js -o dist/libraries/jsruntime-manager.min.js -c warnings=false -m
uglifyjs libraries/pagesection-manager.js -o dist/libraries/pagesection-manager.min.js -c warnings=false -m
uglifyjs libraries/pop-manager.js -o dist/libraries/pop-manager.min.js -c warnings=false -m
uglifyjs libraries/topleveljson.js -o dist/libraries/topleveljson.min.js -c warnings=false -m
uglifyjs libraries/utils.js -o dist/libraries/utils.min.js -c warnings=false -m
uglifyjs libraries/pop-utils.js -o dist/libraries/pop-utils.min.js -c warnings=false -m
uglifyjs libraries/compatibility.js -o dist/libraries/compatibility.min.js -c warnings=false -m
uglifyjs libraries/resourceloader.js -o dist/libraries/resourceloader.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/pop-frontendengine/js/dist/bundles/pop-frontendengine.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-frontendengine/js/libraries&f=helpers.handlebars.min.js,utils.min.js,pop-utils.min.js,compatibility.min.js,jslibrary-manager.min.js,codesplit-jslibrary-manager.min.js,jsruntime-manager.min.js,pagesection-manager.min.js,history.min.js,interceptors.min.js,resourceloader.min.js,pop-manager.min.js,topleveljson.min.js"

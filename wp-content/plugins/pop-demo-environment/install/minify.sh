#---------------------------------------
# EXECUTE MINIFY FOR ALL DEPENDENCIES
#---------------------------------------
#PoP Frontend
bash -x $POP_APP_PATH/wp-content/plugins/pop-frontendengine/build/minify.sh
#PoP Core Processors
bash -x $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/build/minify.sh
#PoP ServiceWorkers
bash -x $POP_APP_PATH/wp-content/plugins/pop-serviceworkers/build/minify.sh
#PoP Theme Wassup
bash -x $POP_APP_PATH/wp-content/plugins/poptheme-wassup/build/minify.sh
#UserAvatar-PoP
bash -x $POP_APP_PATH/wp-content/plugins/pop-useravatar/build/minify.sh
#PhotoSwipe-PoP
bash -x $POP_APP_PATH/wp-content/plugins/photoswipe-pop/build/minify.sh
#Events Manager PoP Processors
bash -x $POP_APP_PATH/wp-content/plugins/events-manager-popprocessors/build/minify.sh
#User Role Editor PoP Processors
bash -x $POP_APP_PATH/wp-content/plugins/user-role-editor-popprocessors/build/minify.sh
#Aryo Activity Log PoP Processors
bash -x $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/build/minify.sh
#Wordpress Social Login PoP Processors
bash -x $POP_APP_PATH/wp-content/plugins/wordpress-social-login-popprocessors/build/minify.sh
#PoP MESYM Section Processors
bash -x $POP_APP_PATH/wp-content/plugins/poptheme-wassup-sectionprocessors/build/minify.sh
#PoP MESYM TPPDebate Processors
bash -x $POP_APP_PATH/wp-content/plugins/poptheme-wassup-votingprocessors/build/minify.sh
#PoP MESYM Organik Processors
bash -x $POP_APP_PATH/wp-content/plugins/poptheme-wassup-organikprocessors/build/minify.sh
#PoP MESYM Category Processors
bash -x $POP_APP_PATH/wp-content/plugins/poptheme-wassup-categoryprocessors/build/minify.sh

#---------------------------------------
# EXECUTE MINIFY FOR ALL THEMES
#---------------------------------------
#GetPoP
bash -x $POP_APP_PATH/wp-content/themes/getpop/build/minify.sh

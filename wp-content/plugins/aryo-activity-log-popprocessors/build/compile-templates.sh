rm $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/aryo-activity-log-popprocessors/js/templates/

handlebars layouts/aal-layout-notificationicon.tmpl -m --extension=tmpl -f ../dist/templates/aal-layout-notificationicon.tmpl.js
handlebars layouts/aal-layout-notificationtime.tmpl -m --extension=tmpl -f ../dist/templates/aal-layout-notificationtime.tmpl.js
handlebars layouts/aal-layout-previewnotification.tmpl -m --extension=tmpl -f ../dist/templates/aal-layout-previewnotification.tmpl.js

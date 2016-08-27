rm $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/pop-useravatar/js/templates/

handlebars fileupload/fileupload-picture-download.tmpl -m --extension=tmpl -f ../dist/templates/fileupload-picture-download.tmpl.js
handlebars fileupload/fileupload-picture-upload.tmpl -m --extension=tmpl -f ../dist/templates/fileupload-picture-upload.tmpl.js
handlebars formcomponents-inputs/formcomponent-fileupload-picture.tmpl -m --extension=tmpl -f ../dist/templates/formcomponent-fileupload-picture.tmpl.js

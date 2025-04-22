#!/bin/bash
# Delete the uploads folder, to avoid duplicated images appending "-1" on the filename
WEBSERVER_DOMAIN="$1"

UPLOADS_DIR=/app/wordpress/wp-content/uploads
if [ -d "$UPLOADS_DIR" ]; then
    printf "\n";
    echo "Deleting the Uploads folder"
    rm -rf $UPLOADS_DIR
fi

wp import /app/_shared-webserver/assets/gatographql-data.xml --url="$(echo $WEBSERVER_DOMAIN)" --authors=create
wp import /app/_shared-webserver/assets/gatographql-testing-data.xml --url="$(echo $WEBSERVER_DOMAIN)" --authors=create

# Importing a JSON object via gatographql-data.xml doesn't work, then do here
wp post meta update 1116 meta_with_object_value '{"_edit_lock":{"to":{"111":["1743667747:1"]}},"_pingme":{"to":{"111":["1"]}},"some_property_to_translate":{"to":{"111":["Messi won another cup for more than 4 times by now, bleh\r\nAnd with another sentence"]}},"some_property":{"to":{"111":["Nothing about Diego?"]}}}' --format=json
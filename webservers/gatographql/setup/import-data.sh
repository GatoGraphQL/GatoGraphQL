#!/bin/bash
# Delete the uploads folder, to avoid duplicated images appending "-1" on the filename
WEBSERVER_DOMAIN="$1"

UPLOADS_DIR=/app/wordpress/wp-content/uploads
if [ -d "$UPLOADS_DIR" ]; then
    printf "\n";
    echo "Deleting the Uploads folder"
    rm -rf $UPLOADS_DIR
fi

wp import /app/assets/gatographql-data.xml --url="$(echo $WEBSERVER_DOMAIN)" --authors=create
wp import /app/assets/gatographql-testing-data.xml --url="$(echo $WEBSERVER_DOMAIN)" --authors=create
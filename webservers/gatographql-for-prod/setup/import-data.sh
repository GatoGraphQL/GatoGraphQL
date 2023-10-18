#!/bin/sh
# Delete the uploads folder, to avoid duplicated images appending "-1" on the filename
UPLOADS_DIR=/app/wordpress/wp-content/uploads
if [ -d "$UPLOADS_DIR" ]; then
    printf "\n";
    echo "Deleting the Uploads folder"
    rm -rf $UPLOADS_DIR
fi

wp import /app/assets/gatographql-data.xml --url="gatographql.lndo.site" --authors=create
wp import /app/assets/gatographql-testing-data.xml --url="gatographql.lndo.site" --authors=create
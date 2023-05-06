#!/bin/sh
# Delete the uploads folder, to avoid duplicated images appending "-1" on the filename
UPLOADS_DIR=/app/wordpress/wp-content/uploads
if [ -d "$UPLOADS_DIR" ]; then
    echo "Deleting the Uploads folder"
    rm -rf $UPLOADS_DIR
fi

wp import /app/assets/gato-graphql-data.xml --url="gato-graphql.lndo.site" --authors=create --path=/app/wordpress
wp import /app/assets/gato-graphql-testing-data.xml --url="gato-graphql.lndo.site" --authors=create --path=/app/wordpress
#!/bin/sh
sed 's/__DIR__/dirname(__DIR__)/' /app/wordpress/index.php > /app/wordpress/Schema/index.php
cp /app/wordpress/Schema/index.php /app/wordpress/API/index.php
cp /app/wordpress/Schema/index.php /app/wordpress/Engine/index.php
cp /app/wordpress/Schema/index.php /app/wordpress/GraphQLByPoP/index.php
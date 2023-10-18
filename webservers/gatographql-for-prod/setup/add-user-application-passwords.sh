#!/bin/sh
echo "Adding Application Passwords for users"
# # These are the already-encoded meta entries for the following application passwords:
# # - user: 1 (username: "admin")
# # - password: "koTB AdvI 2X9h H27B ZYXh TAx7"
# wp user meta add 1 _application_passwords '[{"uuid":"fe8864c0-fb44-4249-ba69-a017ac82ff17","app_id":"","name":"dev_tests","password":"$P$BIwMzE6U15/Xv8pGpqTNwLyoCb1YVh0","created":1666673127,"last_used":1666673144,"last_ip":"172.19.0.2"}]' --format=json
DEV_TESTS_APP_PASSWORD=$(wp user application-password create 1 dev_tests --porcelain)
echo "Added application password $DEV_TESTS_APP_PASSWORD"
wp user meta add 1 app_password $DEV_TESTS_APP_PASSWORD

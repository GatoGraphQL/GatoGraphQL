# For index-local: Add all the config/templates/styles for all the website into the release
cd $POP_APP_PATH/wp-content/themes/tppdebate/plugins/pop-mailer-aws/scripts/lambda/resources/
zip -r $POP_MAILER_AWS_RELEASEFILE .

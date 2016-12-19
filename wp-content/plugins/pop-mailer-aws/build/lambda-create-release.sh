# Delete the previous version of the .zip file
rm $POP_MAILER_AWS_RELEASEFILE

# Include all application files
cd $POP_APP_PATH/wp-content/plugins/pop-mailer-aws/scripts/lambda/code/
zip -r $POP_MAILER_AWS_RELEASEFILE .

# For index-local: Add the generic config/templates/styles into the release
cd $POP_APP_PATH/wp-content/plugins/pop-mailer-aws/scripts/lambda/resources/
zip -r $POP_MAILER_AWS_RELEASEFILE .

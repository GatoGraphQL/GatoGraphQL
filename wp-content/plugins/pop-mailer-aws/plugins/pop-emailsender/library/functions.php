<?php

// Execute last: remove the templates folder since the frame is added in the Lambda function instead
// add_action('sendemail_to_users:template_folder', '__return_false', 9999999);

// Replace the templates by removing the frame, adding only the Header and Footer
add_action('sendemail_to_users:template_folders', 'pop_mailer_templates', 999999);
function pop_mailer_templates($template_folders) {

	array_unshift($template_folders, POP_MAILER_AWS_DIR_RESOURCES.'/email-templates/default/');
	return $template_folders;
}
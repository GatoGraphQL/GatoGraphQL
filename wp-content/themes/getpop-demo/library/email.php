<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_action('sendemail_to_users:template_folders', 'gd_custom_sendemail_to_users');
function gd_custom_sendemail_to_users($template_folders) {

	array_unshift($template_folders, GETPOPDEMO_DIR_RESOURCES.'/email-templates/default/');
	return $template_folders;
}

add_action('sendemail_get_userhtml:posthtml_styles', 'gd_custom_sendemail_bgcolor');
add_action('sendemail_get_userhtml:userhtml_styles', 'gd_custom_sendemail_bgcolor');
add_action('sendemail_to_users_from_comment:comment_styles', 'gd_custom_sendemail_bgcolor');
function gd_custom_sendemail_bgcolor($styles) {

	$styles[] = 'background-color: #f1f1f2';
	return $styles;
}

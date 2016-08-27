<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_action('gd_sendemail_to_users:template_folder', 'gd_custom_sendemail_to_users');
function gd_custom_sendemail_to_users($template) {

	return TPPDEBATE_DIR_RESOURCES.'/email-templates/default/';
}

add_action('gd_sendemail_get_userhtml:posthtml_styles', 'gd_custom_sendemail_bgcolor');
add_action('gd_sendemail_get_userhtml:userhtml_styles', 'gd_custom_sendemail_bgcolor');
add_action('gd_sendemail_to_users_from_comment:comment_styles', 'gd_custom_sendemail_bgcolor');
function gd_custom_sendemail_bgcolor($styles) {

	$styles[] = 'background-color: #f1f1f2';
	return $styles;
}

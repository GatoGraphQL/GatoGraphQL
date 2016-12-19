<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('GD_Template_Processor_CreateUserFormMesageFeedbackLayoutsBase:success:msg', 'poptheme_wassup_createuser_successmsg');
function poptheme_wassup_createuser_successmsg($msg) {

	return sprintf(
		'<p>%s</p>%s%s',
		__('Please add the following emails to your contact list, to make sure you receive our notifications:', 'poptheme-wassup'),
		sprintf(
			'<ul><li>%s</li><li>%s</li></ul>',
			PoP_EmailSender_Utils::get_from_email(),
			PoPTheme_Wassup_EmailSender_Utils::get_newsletter_email()
		),
		$msg
	);
}
<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EMAIL_FRAME_PREFERENCES', 'preferences');

class PoP_EmailSender_Templates_Preferences extends PoP_EmailSender_Templates_Simple {

	function get_name() {

		return GD_EMAIL_FRAME_PREFERENCES;
	}

	// function get_emailframe_footer(/*$frame, */$title, $emails, $names, $template) {
		
	// 	$ret = parent::get_emailframe_footer($title, $emails, $names, $template);
	// 	return PoP_EmailSender_CustomUtils::get_preferences_footer().$ret;
	// }

	function get_emailframe_beforefooter(/*$frame, */$title, $emails, $names, $template) {
		
		return PoP_EmailSender_CustomUtils::get_preferences_footer();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EmailSender_Templates_Preferences();

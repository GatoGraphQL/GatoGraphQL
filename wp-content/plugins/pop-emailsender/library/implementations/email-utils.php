<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_EmailSender_CustomUtils {

	public static function get_preferences_footer($msg = '') {

		$text = sprintf(
			__('You can edit your preferences for email notifications <a href="%s">here</a>.', 'pop-emailsender'),
			get_permalink(POP_COREPROCESSORS_PAGE_MYPREFERENCES)
		);
		return sprintf(
			'<p><small>%s</small></p>',
			$msg ? 
				sprintf(
					__('%s %s', 'pop-emailsender'),
					$msg,
					$text
				) : $text
		);
	}
}
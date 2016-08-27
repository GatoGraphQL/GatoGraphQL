<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_CreateUpdate_Link_Utils {

	public static function validatecontent(&$errors, $form_data) {

		if (empty($form_data['content'])) {
			
			// The link will be the content. So then replace the error message if the content (link) is empty
			// Add the error message at the beginning, since the Link input is shown before the Title input
			array_splice($errors, array_search(__('The content cannot be empty', 'poptheme-wassup'), $errors), 1);
			array_unshift($errors, __('The link cannot be empty', 'poptheme-wassup'));
		}
		else {

			// the content is actually the external URL, so validate it has a right format
			// Taken from http://www.w3schools.com/php/php_form_url_email.asp
			if (!preg_match("/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $form_data['content'])) {
			
				array_unshift($errors, __('Invalid Link URL', 'poptheme-wassup'));
			}
		}
	}
}

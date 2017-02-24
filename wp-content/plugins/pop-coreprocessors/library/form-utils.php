<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Gravity Forms Utils
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_FormUtils {

	/**
	 * Function to allow Gravity Forms to use the information from the logged in user, eg: Contact us form, get the user email and name
	 * By default it's true, but GetPoP website logs the user in for testing purposes, so all fields in the Contact us form must still be filled by the user
	 */
	public static function use_loggedinuser_data() {

		return apply_filters('PoP_FormUtils:use_loggedinuser_data', true);
	}
}
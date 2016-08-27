<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Utils functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('TPPDEBATE_COUNTRY_AR', 'ar');
define ('TPPDEBATE_COUNTRY_MY', 'my');

class TPPDebate_Utils {

	// This theme can serve 2 websites: TPPDebate AR and MY
	public static function get_country() {

		if (defined('TPPDEBATE_AR_ENVIRONMENT_VERSION')) {

			return TPPDEBATE_COUNTRY_AR;
		}
		elseif (defined('TPPDEBATE_MY_ENVIRONMENT_VERSION')) {

			return TPPDEBATE_COUNTRY_MY;
		}

		return null;
	}
}
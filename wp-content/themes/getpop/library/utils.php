<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Utils functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoP_Utils {

	// This theme can serve 2 websites: GetPoP, and GetPoP Demo. Check if it is one or the other through its version definitions
	public static function is_demo() {

		return defined('GETPOPDEMO_ENVIRONMENT_VERSION') || defined('POPDEMO_ENVIRONMENT_VERSION');
	}
}
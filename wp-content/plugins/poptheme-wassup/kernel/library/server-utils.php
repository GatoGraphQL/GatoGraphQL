<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_ServerUtils {

	public static function enable_flush_rules() {

		if (defined('POP_SERVER_ENABLEFLUSHRULES')) {
			return POP_SERVER_ENABLEFLUSHRULES;
		}

		// By default do NOT allow to flush rules, because:
		// It will generate a HUGE sql query, whose execution takes the latency way up, and it will consume a HUGE bandwidth between EC2 and the DB, costing real $$$
		return false;
	}
}
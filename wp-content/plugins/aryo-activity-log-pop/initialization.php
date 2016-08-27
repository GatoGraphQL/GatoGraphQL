<?php
class AAL_PoP_Initialization {

	function initialize() {

		global $wpdb;

		load_plugin_textdomain('aal-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

		// set up our DB name
		$wpdb->activity_log_status = $wpdb->prefix . 'aryo_activity_log_status';

		/**---------------------------------------------------------------------------------------------------------------
		 * Constants/Configuration for functionalities needed by the plug-in
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';
	}
}
<?php
class PoP_AutomatedEmails_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-automatedemails', false, dirname(plugin_basename(__FILE__)).'/languages');
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Kernel
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Plugins Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';
	}
}
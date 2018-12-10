<?php
class PoPCMS_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-cms', false, dirname(plugin_basename(__FILE__)).'/languages');

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Kernel
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';
	}
}
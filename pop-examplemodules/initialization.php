<?php
class PoP_ExampleModules_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-examplemodules', false, dirname(plugin_basename(__FILE__)).'/languages');

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';
	}
}
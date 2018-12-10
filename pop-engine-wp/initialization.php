<?php
class PoP_EngineWP_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-engine-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';
	}
}
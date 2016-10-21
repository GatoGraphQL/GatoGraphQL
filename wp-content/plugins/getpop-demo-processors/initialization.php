<?php
class GetPoPDemo_Processors_Initialization {

	function initialize(){

		// load_plugin_textdomain('getpop-demo-processors', false, dirname(plugin_basename(__FILE__)).'/languages');
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
	}
}
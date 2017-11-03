<?php
class GetPoPDemo_Processors_Initialization {

	function initialize(){

		load_plugin_textdomain('getpop-demo-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('am');

		/**---------------------------------------------------------------------------------------------------------------
		 * Global Variables and Configuration from CUSTOM folder
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Plug-ins
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';
	}
}
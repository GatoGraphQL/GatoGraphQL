<?php
class PoP_GenericFormsProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-genericforms-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('b1');

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		// require_once 'library/load.php';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_GenericFormsProcessors_Initialization;
$PoP_GenericFormsProcessors_Initialization = new PoP_GenericFormsProcessors_Initialization();
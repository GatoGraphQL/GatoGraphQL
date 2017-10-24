<?php
class PoP_Core_ServerSide_Functions {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	function expandJSKeys(&$args) {
	
		$context = &$args['context'];

		if ($context[GD_JS_FONTAWESOME]) {
			$context['fontawesome'] = $context[GD_JS_FONTAWESOME];
		}
		if ($context[GD_JS_DESCRIPTION]) {
			$context['description'] = $context[GD_JS_DESCRIPTION];
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (PoP_Frontend_ServerUtils::use_serverside_rendering()) {
	$pop_core_serverside_functions = new PoP_Core_ServerSide_Functions();
	$popJSLibraryManager = PoP_ServerSide_Libraries_Factory::get_jslibrary_instance();
	$popJSLibraryManager->register($pop_core_serverside_functions, array('expandJSKeys'));
}
<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-frontendengine/js/helpers.handlebars.js
*/
class EM_PoPProcessors_ServerSide_Helpers {

	function locationsPageURL($domain, $options) {

	    // Allow PoP MultiDomain to add the page URLs for other domains
	    return EM_PoPProcessors_ConstantsUtils::get_location_page_url($domain);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $em_popprocessors_serverside_helpers;
$em_popprocessors_serverside_helpers = new EM_PoPProcessors_ServerSide_Helpers();
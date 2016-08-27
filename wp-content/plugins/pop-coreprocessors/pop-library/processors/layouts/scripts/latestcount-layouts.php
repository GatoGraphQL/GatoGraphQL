<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_LATESTCOUNTSCRIPT', PoP_ServerUtils::get_template_definition('layout-latestcount-script'));

class GD_Template_Processor_LatestCountScriptsLayouts extends GD_Template_Processor_LatestCountScriptsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_LATESTCOUNTSCRIPT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LatestCountScriptsLayouts();
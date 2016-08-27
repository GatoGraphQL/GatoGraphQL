<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTUSER_QUICKLINKS', PoP_ServerUtils::get_template_definition('layoutuser-quicklinks'));

class GD_Template_Processor_UserQuickLinkLayouts extends GD_Template_Processor_UserQuickLinkLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTUSER_QUICKLINKS
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserQuickLinkLayouts();
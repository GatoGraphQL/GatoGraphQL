<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLUSERTITLE', PoP_TemplateIDUtils::get_template_definition('singlelayout-fullusertitle'));

class GD_Template_Processor_CustomFullUserTitleLayouts extends GD_Template_Processor_FullUserTitleLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLUSERTITLE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomFullUserTitleLayouts();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_LAYOUT_LOCATIONADDRESS', PoP_ServerUtils::get_template_definition('em-layout-address'));

class GD_Template_Processor_LocationAddressLayouts extends GD_Template_Processor_LocationAddressLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_LAYOUT_LOCATIONADDRESS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationAddressLayouts();
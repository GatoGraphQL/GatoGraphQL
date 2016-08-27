<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUT_PROFILEORGANIZATION_DETAILS', PoP_ServerUtils::get_template_definition('ure-layoutuser-profileorganization-details'));

class GD_URE_Custom_Template_Processor_ProfileOrganizationLayouts extends GD_URE_Custom_Template_Processor_ProfileOrganizationLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUT_PROFILEORGANIZATION_DETAILS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_ProfileOrganizationLayouts();
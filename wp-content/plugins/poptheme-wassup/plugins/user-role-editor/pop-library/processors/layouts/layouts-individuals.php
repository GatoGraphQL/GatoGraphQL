<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS', PoP_ServerUtils::get_template_definition('ure-layoutuser-profileindividual-details'));

class GD_URE_Custom_Template_Processor_ProfileIndividualLayouts extends GD_URE_Custom_Template_Processor_ProfileIndividualLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_ProfileIndividualLayouts();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT', PoP_ServerUtils::get_template_definition('layoutuser-typeahead-component'));

class GD_Template_Processor_UserTypeaheadComponentLayouts extends GD_Template_Processor_UserTypeaheadComponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserTypeaheadComponentLayouts();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT', PoP_TemplateIDUtils::get_template_definition('em-layoutlocation-typeahead-component'));

class GD_EM_Template_Processor_LocationTypeaheadsComponentLayouts extends GD_EM_Template_Processor_LocationTypeaheadsComponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_LocationTypeaheadsComponentLayouts();
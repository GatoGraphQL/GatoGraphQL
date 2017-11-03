<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTPOST_TYPEAHEAD_COMPONENT', PoP_TemplateIDUtils::get_template_definition('layoutpost-typeahead-component'));

class GD_Template_Processor_PostTypeaheadComponentLayouts extends GD_Template_Processor_PostTypeaheadComponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTPOST_TYPEAHEAD_COMPONENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostTypeaheadComponentLayouts();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT', PoP_ServerUtils::get_template_definition('layoutstatic-typeahead-component'));

class GD_Template_Processor_StaticTypeaheadComponentLayouts extends GD_Template_Processor_StaticTypeaheadComponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_StaticTypeaheadComponentLayouts();
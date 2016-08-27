<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTTAG_TYPEAHEAD_COMPONENT', PoP_ServerUtils::get_template_definition('layouttag-typeahead-component'));

class GD_Template_Processor_TagTypeaheadComponentLayouts extends GD_Template_Processor_TagTypeaheadComponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTTAG_TYPEAHEAD_COMPONENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TagTypeaheadComponentLayouts();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_SELECTED', PoP_TemplateIDUtils::get_template_definition('em-layoutlocation-typeahead-selected'));

class GD_EM_Template_Processor_LocationTypeaheadsSelectedLayouts extends GD_EM_Template_Processor_LocationTypeaheadsSelectedLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_SELECTED,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_LocationTypeaheadsSelectedLayouts();
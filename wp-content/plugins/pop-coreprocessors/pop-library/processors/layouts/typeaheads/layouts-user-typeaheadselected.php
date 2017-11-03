<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED', PoP_TemplateIDUtils::get_template_definition('layoutuser-typeahead-selected'));
define ('GD_TEMPLATE_LAYOUTUSER_FILTERTYPEAHEAD_SELECTED', PoP_TemplateIDUtils::get_template_definition('layoutuser-filtertypeahead-selected'));

class GD_Template_Processor_UserTypeaheadSelectedLayouts extends GD_Template_Processor_UserTypeaheadSelectedLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED,
			GD_TEMPLATE_LAYOUTUSER_FILTERTYPEAHEAD_SELECTED,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserTypeaheadSelectedLayouts();
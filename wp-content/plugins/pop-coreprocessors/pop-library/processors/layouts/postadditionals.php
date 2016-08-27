<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL', PoP_ServerUtils::get_template_definition('layout-postadditional-multilayout-label'));

class GD_Template_Processor_PostAdditionalLayouts extends GD_Template_Processor_PostAdditionalLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostAdditionalLayouts();
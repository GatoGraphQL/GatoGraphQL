<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_HIDEIFEMPTY', PoP_ServerUtils::get_template_definition('hideifempty'));

class GD_Template_Processor_HideIfEmpties extends GD_Template_Processor_HideIfEmptyBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_HIDEIFEMPTY
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_HideIfEmpties();
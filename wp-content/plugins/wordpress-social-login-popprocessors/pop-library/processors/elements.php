<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WSL_NETWORKLINKS', PoP_TemplateIDUtils::get_template_definition('wsl-networklinks'));

class GD_Template_Processor_WSLElements extends GD_Template_Processor_WSLElementsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WSL_NETWORKLINKS
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_WSLElements();
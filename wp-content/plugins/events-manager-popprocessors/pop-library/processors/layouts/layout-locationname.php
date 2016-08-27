<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_LAYOUT_LOCATIONNAME', PoP_ServerUtils::get_template_definition('em-layout-locationname'));

class GD_Template_Processor_LocationNameLayouts extends GD_Template_Processor_LocationNameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_LAYOUT_LOCATIONNAME,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationNameLayouts();
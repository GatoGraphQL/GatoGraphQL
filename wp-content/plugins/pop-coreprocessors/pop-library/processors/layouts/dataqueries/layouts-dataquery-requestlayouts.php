<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_DATAQUERY_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('layout-dataquery-requestlayouts'));

class GD_Template_Processor_RequestLayouts extends GD_Template_Processor_RequestLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_DATAQUERY_REQUESTLAYOUTS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_RequestLayouts();
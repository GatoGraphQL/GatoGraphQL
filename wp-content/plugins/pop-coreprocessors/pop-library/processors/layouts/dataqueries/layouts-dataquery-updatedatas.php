<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Embed
define ('GD_TEMPLATE_LAYOUT_DATAQUERY_UPDATEDATA', PoP_TemplateIDUtils::get_template_definition('layout-dataquery-updatedata'));

class GD_Template_Processor_DataQuery_UpdateDataLayouts extends GD_Template_Processor_DataQuery_UpdateDataLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_DATAQUERY_UPDATEDATA,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DataQuery_UpdateDataLayouts();
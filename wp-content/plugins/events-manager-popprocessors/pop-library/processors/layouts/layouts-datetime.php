<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_LAYOUT_DATETIME', PoP_ServerUtils::get_template_definition('em-layout-datetime'));
define ('GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS', PoP_ServerUtils::get_template_definition('em-layout-datetimedownloadlinks'));

class GD_EM_Template_Processor_DateTimeLayouts extends GD_EM_Template_Processor_DateTimeLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_LAYOUT_DATETIME,
			GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS,
		);
	}

	function add_downloadlinks($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS:

				return true;
		}

		return parent::add_downloadlinks($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_DateTimeLayouts();
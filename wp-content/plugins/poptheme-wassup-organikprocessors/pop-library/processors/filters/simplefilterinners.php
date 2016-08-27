<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIMPLEFILTERINNER_FARMS', PoP_ServerUtils::get_template_definition('simplefilterinner-farms'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORFARMS', PoP_ServerUtils::get_template_definition('simplefilterinner-authorfarms'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYFARMS', PoP_ServerUtils::get_template_definition('simplefilterinner-myfarms'));

class OP_Template_Processor_CustomSimpleFilterInners extends GD_Template_Processor_SimpleFilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIMPLEFILTERINNER_FARMS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORFARMS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYFARMS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_SIMPLEFILTERINNER_FARMS => GD_FILTER_FARMS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORFARMS => GD_FILTER_AUTHORFARMS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYFARMS => GD_FILTER_MYFARMS,
		);
		if ($filter = $filters[$template_id]) {

			return $filter;
		}
		
		return parent::get_filter($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomSimpleFilterInners();
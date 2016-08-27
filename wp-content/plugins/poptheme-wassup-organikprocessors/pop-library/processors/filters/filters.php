<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_AUTHORFARMS', PoP_ServerUtils::get_template_definition('filter-authorfarms'));
define ('GD_TEMPLATE_FILTER_MYFARMS', PoP_ServerUtils::get_template_definition('filter-myfarms'));
define ('GD_TEMPLATE_FILTER_FARMS', PoP_ServerUtils::get_template_definition('filter-farms'));

class OP_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_FARMS,
			GD_TEMPLATE_FILTER_AUTHORFARMS,
			GD_TEMPLATE_FILTER_MYFARMS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_FARMS => GD_TEMPLATE_FILTERINNER_FARMS,
			GD_TEMPLATE_FILTER_AUTHORFARMS => GD_TEMPLATE_FILTERINNER_AUTHORFARMS,
			GD_TEMPLATE_FILTER_MYFARMS => GD_TEMPLATE_FILTERINNER_MYFARMS,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}
	
		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomFilters();
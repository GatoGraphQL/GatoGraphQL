<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORFARMS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorfarms'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYFARMS', PoP_ServerUtils::get_template_definition('delegatorfilter-myfarms'));
define ('GD_TEMPLATE_DELEGATORFILTER_FARMS', PoP_ServerUtils::get_template_definition('delegatorfilter-farms'));

class OP_Template_Processor_CustomDelegatorFilters extends GD_Template_Processor_CustomDelegatorFiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DELEGATORFILTER_AUTHORFARMS,
			GD_TEMPLATE_DELEGATORFILTER_MYFARMS,
			GD_TEMPLATE_DELEGATORFILTER_FARMS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_DELEGATORFILTER_AUTHORFARMS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORFARMS,
			GD_TEMPLATE_DELEGATORFILTER_MYFARMS => GD_TEMPLATE_SIMPLEFILTERINNER_MYFARMS,
			GD_TEMPLATE_DELEGATORFILTER_FARMS => GD_TEMPLATE_SIMPLEFILTERINNER_FARMS,
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
new OP_Template_Processor_CustomDelegatorFilters();
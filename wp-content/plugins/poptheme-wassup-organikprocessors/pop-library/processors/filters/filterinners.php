<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_FARMS', PoP_ServerUtils::get_template_definition('filterinner-farms'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORFARMS', PoP_ServerUtils::get_template_definition('filterinner-authorfarms'));
define ('GD_TEMPLATE_FILTERINNER_TAGFARMS', PoP_ServerUtils::get_template_definition('filterinner-tagfarms'));
define ('GD_TEMPLATE_FILTERINNER_MYFARMS', PoP_ServerUtils::get_template_definition('filterinner-myfarms'));

class OP_Template_Processor_CustomFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_FARMS,
			GD_TEMPLATE_FILTERINNER_TAGFARMS,
			GD_TEMPLATE_FILTERINNER_AUTHORFARMS,
			GD_TEMPLATE_FILTERINNER_MYFARMS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_FILTERINNER_FARMS => GD_FILTER_FARMS,
			GD_TEMPLATE_FILTERINNER_TAGFARMS => GD_FILTER_TAGFARMS,
			GD_TEMPLATE_FILTERINNER_AUTHORFARMS => GD_FILTER_AUTHORFARMS,
			GD_TEMPLATE_FILTERINNER_MYFARMS => GD_FILTER_MYFARMS,
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
new OP_Template_Processor_CustomFilterInners();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_INDIVIDUALS', PoP_ServerUtils::get_template_definition('filterinner-individuals'));
define ('GD_TEMPLATE_FILTERINNER_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('filterinner-organizations'));
define ('GD_TEMPLATE_FILTERINNER_MYMEMBERS', PoP_ServerUtils::get_template_definition('filterinner-mymembers'));

class GD_URE_Template_Processor_CustomFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_INDIVIDUALS,
			GD_TEMPLATE_FILTERINNER_ORGANIZATIONS,
			GD_TEMPLATE_FILTERINNER_MYMEMBERS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_FILTERINNER_INDIVIDUALS => GD_FILTER_INDIVIDUALS,
			GD_TEMPLATE_FILTERINNER_ORGANIZATIONS => GD_FILTER_ORGANIZATIONS,
			GD_TEMPLATE_FILTERINNER_MYMEMBERS => GD_FILTER_MYMEMBERS,
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
new GD_URE_Template_Processor_CustomFilterInners();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_INDIVIDUALS', PoP_ServerUtils::get_template_definition('filter-individuals'));
define ('GD_TEMPLATE_FILTER_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('filter-organizations'));
define ('GD_TEMPLATE_FILTER_MYMEMBERS', PoP_ServerUtils::get_template_definition('filter-mymembers'));

class GD_URE_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_INDIVIDUALS,
			GD_TEMPLATE_FILTER_ORGANIZATIONS,
			GD_TEMPLATE_FILTER_MYMEMBERS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_INDIVIDUALS => GD_TEMPLATE_FILTERINNER_INDIVIDUALS,
			GD_TEMPLATE_FILTER_ORGANIZATIONS => GD_TEMPLATE_FILTERINNER_ORGANIZATIONS,
			GD_TEMPLATE_FILTER_MYMEMBERS => GD_TEMPLATE_FILTERINNER_MYMEMBERS,
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
new GD_URE_Template_Processor_CustomFilters();
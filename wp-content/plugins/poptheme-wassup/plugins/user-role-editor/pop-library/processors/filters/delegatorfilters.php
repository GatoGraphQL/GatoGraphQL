<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DELEGATORFILTER_INDIVIDUALS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-individuals'));
define ('GD_TEMPLATE_DELEGATORFILTER_ORGANIZATIONS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-organizations'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYMEMBERS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-mymembers'));

class GD_URE_Template_Processor_CustomDelegatorFilters extends GD_Template_Processor_CustomDelegatorFiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DELEGATORFILTER_INDIVIDUALS,
			GD_TEMPLATE_DELEGATORFILTER_ORGANIZATIONS,
			GD_TEMPLATE_DELEGATORFILTER_MYMEMBERS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_DELEGATORFILTER_INDIVIDUALS => GD_TEMPLATE_SIMPLEFILTERINNER_INDIVIDUALS,
			GD_TEMPLATE_DELEGATORFILTER_ORGANIZATIONS => GD_TEMPLATE_SIMPLEFILTERINNER_ORGANIZATIONS,
			GD_TEMPLATE_DELEGATORFILTER_MYMEMBERS => GD_TEMPLATE_SIMPLEFILTERINNER_MYMEMBERS,
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
new GD_URE_Template_Processor_CustomDelegatorFilters();
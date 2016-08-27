<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIMPLEFILTERINNER_INDIVIDUALS', PoP_ServerUtils::get_template_definition('simplefilterinner-individuals'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('simplefilterinner-organizations'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYMEMBERS', PoP_ServerUtils::get_template_definition('simplefilterinner-mymembers'));

class GD_URE_Template_Processor_CustomSimpleFilterInners extends GD_Template_Processor_SimpleFilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIMPLEFILTERINNER_INDIVIDUALS,
			GD_TEMPLATE_SIMPLEFILTERINNER_ORGANIZATIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYMEMBERS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_SIMPLEFILTERINNER_INDIVIDUALS => GD_FILTER_INDIVIDUALS,
			GD_TEMPLATE_SIMPLEFILTERINNER_ORGANIZATIONS => GD_FILTER_ORGANIZATIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYMEMBERS => GD_FILTER_MYMEMBERS,
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
new GD_URE_Template_Processor_CustomSimpleFilterInners();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_INSTANTANEOUSFILTER_CATEGORIES', PoP_ServerUtils::get_template_definition('instantaneousfilter-categories'));
define ('GD_TEMPLATE_INSTANTANEOUSFILTER_SECTIONS', PoP_ServerUtils::get_template_definition('instantaneousfilter-sections'));
define ('GD_TEMPLATE_INSTANTANEOUSFILTER_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('instantaneousfilter-webpostsections'));

class GD_Template_Processor_InstantaneousFilters extends GD_Template_Processor_InstantaneousFiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_INSTANTANEOUSFILTER_CATEGORIES,
			GD_TEMPLATE_INSTANTANEOUSFILTER_SECTIONS,
			GD_TEMPLATE_INSTANTANEOUSFILTER_WEBPOSTSECTIONS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_INSTANTANEOUSFILTER_CATEGORIES => GD_TEMPLATE_INSTANTANEOUSFILTERINNER_CATEGORIES,
			GD_TEMPLATE_INSTANTANEOUSFILTER_SECTIONS => GD_TEMPLATE_INSTANTANEOUSFILTERINNER_SECTIONS,
			GD_TEMPLATE_INSTANTANEOUSFILTER_WEBPOSTSECTIONS => GD_TEMPLATE_INSTANTANEOUSFILTERINNER_WEBPOSTSECTIONS,
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
new GD_Template_Processor_InstantaneousFilters();
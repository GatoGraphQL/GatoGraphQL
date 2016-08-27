<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_COMMUNITIES_MAP', PoP_ServerUtils::get_template_definition('scroll-communities-map'));
define ('GD_TEMPLATE_SCROLL_ORGANIZATIONS_MAP', PoP_ServerUtils::get_template_definition('scroll-organizations-map'));
define ('GD_TEMPLATE_SCROLL_INDIVIDUALS_MAP', PoP_ServerUtils::get_template_definition('scroll-individuals-map'));
define ('GD_TEMPLATE_SCROLL_AUTHORMEMBERS_MAP', PoP_ServerUtils::get_template_definition('scroll-authormembers-map'));

class GD_URE_Template_Processor_CustomScrollMaps extends GD_Template_Processor_ScrollMapsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_COMMUNITIES_MAP,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_MAP,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_MAP,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_MAP,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_COMMUNITIES_MAP => GD_TEMPLATE_SCROLLINNER_COMMUNITIES_MAP,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_MAP => GD_TEMPLATE_SCROLLINNER_ORGANIZATIONS_MAP,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_MAP => GD_TEMPLATE_SCROLLINNER_INDIVIDUALS_MAP,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_MAP => GD_TEMPLATE_SCROLLINNER_AUTHORMEMBERS_MAP,
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
new GD_URE_Template_Processor_CustomScrollMaps();
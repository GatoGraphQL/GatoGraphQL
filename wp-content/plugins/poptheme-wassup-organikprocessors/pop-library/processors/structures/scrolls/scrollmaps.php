<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_FARMS_MAP', PoP_ServerUtils::get_template_definition('scroll-farms-map'));

class OP_Template_Processor_CustomScrollMaps extends GD_Template_Processor_ScrollMapsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_FARMS_MAP,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_FARMS_MAP => GD_TEMPLATE_SCROLLINNER_FARMS_MAP,
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
new OP_Template_Processor_CustomScrollMaps();
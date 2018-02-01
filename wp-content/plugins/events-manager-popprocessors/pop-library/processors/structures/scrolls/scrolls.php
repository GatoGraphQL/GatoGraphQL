<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('GD_TEMPLATE_SCROLL_LOCATIONS', PoP_TemplateIDUtils::get_template_definition('scroll-locations'));
define ('GD_TEMPLATE_SCROLL_STATICIMAGE', PoP_TemplateIDUtils::get_template_definition('scroll-staticimage'));
define ('GD_TEMPLATE_SCROLL_STATICIMAGE_USERORPOST', PoP_TemplateIDUtils::get_template_definition('scroll-staticimage-userorpost'));

class GD_EM_Template_Processor_Scrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_SCROLL_LOCATIONS,
			GD_TEMPLATE_SCROLL_STATICIMAGE,
			GD_TEMPLATE_SCROLL_STATICIMAGE_USERORPOST,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			// GD_TEMPLATE_SCROLL_LOCATIONS => GD_TEMPLATE_SCROLLINNER_LOCATIONS,
			GD_TEMPLATE_SCROLL_STATICIMAGE => GD_TEMPLATE_MAP_STATICIMAGE,
			GD_TEMPLATE_SCROLL_STATICIMAGE_USERORPOST => GD_TEMPLATE_MAP_STATICIMAGE_USERORPOST,
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
new GD_EM_Template_Processor_Scrolls();
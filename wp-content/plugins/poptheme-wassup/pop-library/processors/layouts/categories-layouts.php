<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_LINK_CATEGORIES', PoP_ServerUtils::get_template_definition('layout-link-categories'));
define ('GD_TEMPLATE_LAYOUT_CATEGORIES', PoP_ServerUtils::get_template_definition('layout-categories'));
define ('GD_TEMPLATE_LAYOUT_APPLIESTO', PoP_ServerUtils::get_template_definition('layout-appliesto'));

class Wassup_Template_Processor_CategoriesLayouts extends GD_Template_Processor_CategoriesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_LINK_CATEGORIES,
			GD_TEMPLATE_LAYOUT_CATEGORIES,
			GD_TEMPLATE_LAYOUT_APPLIESTO,
		);
	}

	function get_categories_field($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_LINK_CATEGORIES:

				return 'linkcategories-strings';

			case GD_TEMPLATE_LAYOUT_CATEGORIES:

				return 'categories-strings';

			case GD_TEMPLATE_LAYOUT_APPLIESTO:

				return 'appliesto-strings';
		}
		
		return parent::get_categories_field($template_id, $atts);
	}
	function get_label_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_APPLIESTO:

				return 'label-primary';
		}
		
		return parent::get_label_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_CategoriesLayouts();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_ORGANIZATIONCATEGORIES', PoP_ServerUtils::get_template_definition('layout-organizationcategories'));
define ('GD_TEMPLATE_LAYOUT_ORGANIZATIONTYPES', PoP_ServerUtils::get_template_definition('layout-organizationtypes'));

class GD_URE_Template_Processor_CategoriesLayouts extends GD_Template_Processor_CategoriesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_ORGANIZATIONCATEGORIES,
			GD_TEMPLATE_LAYOUT_ORGANIZATIONTYPES,
		);
	}

	function get_categories_field($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_ORGANIZATIONCATEGORIES:

				return 'organizationcategories-strings';

			case GD_TEMPLATE_LAYOUT_ORGANIZATIONTYPES:

				return 'organizationtypes-strings';
		}
		
		return parent::get_categories_field($template_id, $atts);
	}
	function get_label_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_ORGANIZATIONTYPES:

				return 'label-primary';
		}
		
		return parent::get_label_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CategoriesLayouts();
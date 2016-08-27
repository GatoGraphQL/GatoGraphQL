<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FARM_CATEGORIES', PoP_ServerUtils::get_template_definition('layout-farm-categories'));

class OP_Template_Processor_Layouts extends GD_Template_Processor_CategoriesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FARM_CATEGORIES,
		);
	}

	function get_categories_field($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FARM_CATEGORIES:

				return 'farmcategories-strings';
		}
		
		return parent::get_categories_field($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_Layouts();
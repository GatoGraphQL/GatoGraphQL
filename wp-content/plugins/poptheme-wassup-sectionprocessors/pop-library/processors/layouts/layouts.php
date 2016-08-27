<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PROJECT_CATEGORIES', PoP_ServerUtils::get_template_definition('layout-project-categories'));
define ('GD_TEMPLATE_LAYOUT_DISCUSSION_CATEGORIES', PoP_ServerUtils::get_template_definition('layout-discussion-categories'));

class GD_Custom_Template_Processor_Layouts extends GD_Template_Processor_CategoriesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PROJECT_CATEGORIES,
			GD_TEMPLATE_LAYOUT_DISCUSSION_CATEGORIES
		);
	}

	function get_categories_field($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PROJECT_CATEGORIES:

				return 'projectcategories-strings';

			case GD_TEMPLATE_LAYOUT_DISCUSSION_CATEGORIES:

				return 'discussioncategories-strings';
		}
		
		return parent::get_categories_field($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_Layouts();
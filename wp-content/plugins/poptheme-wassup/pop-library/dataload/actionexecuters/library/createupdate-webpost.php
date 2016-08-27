<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_WebPost extends GD_CustomCreateUpdate_Post {

	protected function get_categories($form_data) {

		// The section is mandatory. Add it as a category
		$categories = array(
			POPTHEME_WASSUP_CAT_WEBPOSTS,
		);
		// if (PoPTheme_Wassup_Utils::add_webpost_sections()) {
		$sections = $form_data['sections'];
		$categories = array_merge(
			$categories,
			$sections
		);
		// }
		return $categories;
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;

		// We might decide to allow the user to input many sections, or only one section, so this value might be an array or just the value
		// So treat it always as an array
		$form_data['sections'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS)->get_value(GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS, $atts);
		if (!is_array($form_data['sections'])) {
			$form_data['sections'] = array($form_data['sections']);
		}
		
		return $form_data;
	}
}
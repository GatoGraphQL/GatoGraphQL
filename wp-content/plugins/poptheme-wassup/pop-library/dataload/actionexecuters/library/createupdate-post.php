<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CustomCreateUpdate_Post extends GD_CreateUpdate_Post {

	protected function volunteer() {

		return false;
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;

		$form_data['categories'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CATEGORIES)->get_value(GD_TEMPLATE_FORMCOMPONENT_CATEGORIES, $atts);

		// Only if the Volunteering is enabled
		if (defined('POP_GENERICFORMS_PAGE_VOLUNTEER')) {
		
			if ($this->volunteer()) {
				$form_data['volunteersneeded'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT)->get_value(GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT, $atts);
			}
		}

		if (PoPTheme_Wassup_Utils::add_appliesto()) {
			$form_data['appliesto'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_APPLIESTO)->get_value(GD_TEMPLATE_FORMCOMPONENT_APPLIESTO, $atts);
		}
		
		return $form_data;
	}

	protected function additionals($post_id, $form_data) {

		global $gd_template_processor_manager;

		// Categories
		GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_CATEGORIES, $form_data['categories']);

		// Only if the Volunteering is enabled
		if (defined('POP_GENERICFORMS_PAGE_VOLUNTEER')) {
		
			// Volunteers Needed?
			if ($this->volunteer()) {
				GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_VOLUNTEERSNEEDED, $form_data['volunteersneeded'], true);
			}
		}
	
		if (PoPTheme_Wassup_Utils::add_appliesto()) {
			GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_APPLIESTO, $form_data['appliesto']);
		}
	}
}

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_LocationPost extends GD_CustomCreateUpdate_Post {

	protected function get_categories($form_data) {

		return array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS);
	}

	protected function volunteer() {

		return true;
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;
		
		$form_data = array_merge(
			$form_data,
			array(
				// 'locationpostcategories' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES)->get_value(GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES, $atts),
				'locations' => $gd_template_processor_manager->get_processor(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP)->get_value(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts),
			)
		);
		
		return $form_data;
	}

	protected function additionals($post_id, $form_data) {

		global $gd_template_processor_manager;

		parent::additionals($post_id, $form_data);
		
		// Action Categories
		// GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_LOCATIONPOSTCATEGORIES, $form_data['locationpostcategories']);

		// Locations
		GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_LOCATIONS, $form_data['locations']);
	}
}
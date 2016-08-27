<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Discussion extends GD_CustomCreateUpdate_Post {

	protected function get_categories($form_data) {

		return array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS);
	}

	// protected function get_form_data($atts) {

	// 	$form_data = parent::get_form_data($atts);

	// 	global $gd_template_processor_manager;
		
	// 	$form_data = array_merge(
	// 		$form_data,
	// 		array(
	// 			'discussioncategories' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES)->get_value(GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES, $atts)
	// 		)
	// 	);
		
	// 	return $form_data;
	// }

	// protected function additionals($post_id, $form_data) {

	// 	global $gd_template_processor_manager;
		
	// 	// Categories
	// 	GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_DISCUSSIONCATEGORIES, $form_data['discussioncategories']);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_discussion;
// $gd_createupdate_discussion = new GD_CreateUpdate_Discussion();
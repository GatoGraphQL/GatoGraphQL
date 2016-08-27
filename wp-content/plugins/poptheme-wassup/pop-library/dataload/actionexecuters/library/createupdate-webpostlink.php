<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_WebPostLink extends GD_CreateUpdate_WebPost {

	protected function get_categories($form_data) {

		$ret = parent::get_categories($form_data);
		$ret[] = POPTHEME_WASSUP_CAT_WEBPOSTLINKS;
		return $ret;
	}

	protected function validatecontent(&$errors, $form_data) {

		parent::validatecontent($errors, $form_data);
		Wassup_CreateUpdate_Link_Utils::validatecontent($errors, $form_data);
	}

	protected function get_editor_input() {

		return GD_TEMPLATE_FORMCOMPONENT_LINK;
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;
		
		if (PoPTheme_Wassup_Utils::add_link_accesstype()) {

			$form_data = array_merge(
				$form_data,
				array(
					'linkaccess' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LINKACCESS)->get_value(GD_TEMPLATE_FORMCOMPONENT_LINKACCESS, $atts),
					// 'linkcategories' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES)->get_value(GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES, $atts)
				)
			);
		}
		
		return $form_data;
	}

	protected function additionals($post_id, $form_data) {

		global $gd_template_processor_manager;

		parent::additionals($post_id, $form_data);
		
		if (PoPTheme_Wassup_Utils::add_link_accesstype()) {
			
			GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_LINKACCESS, $form_data['linkaccess'], true);
			// GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_LINKCATEGORIES, $form_data['linkcategories']);
		}
	}
}
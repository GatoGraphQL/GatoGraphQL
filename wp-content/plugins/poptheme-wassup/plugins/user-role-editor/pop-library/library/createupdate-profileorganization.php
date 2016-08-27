<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_CreateUpdate_ProfileOrganization extends GD_CreateUpdate_ProfileOrganization {

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;
		
		$form_data = array_merge(
			$form_data,
			GD_URE_CreateUpdate_Profile_Utils::get_form_data($atts),
			array(
				'organizationtypes' => $gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES, $atts),
				'organizationcategories' => $gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES, $atts),
				'contact_number' => trim(esc_attr($gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER, $atts))),
				'contact_person' => trim(esc_attr($gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON, $atts))),
			)
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_custom_createupdate_profileorganization:form_data', $form_data, $atts);
		
		return $form_data;
	}

	protected function additionals_create($user_id, $form_data) {

		parent::additionals_create($user_id, $form_data);
		do_action('gd_custom_createupdate_profileorganization:additionals_create', $user_id, $form_data);
	}
	protected function additionals_update($user_id, $form_data) {

		parent::additionals_update($user_id, $form_data);
		do_action('gd_custom_createupdate_profileorganization:additionals_update', $user_id, $form_data);
	}
	protected function additionals($user_id, $form_data) {

		parent::additionals($user_id, $form_data);		
		do_action('gd_custom_createupdate_profileorganization:additionals', $user_id, $form_data);	
	}
	protected function createupdateuser($user_id, $form_data) {

		parent::createupdateuser($user_id, $form_data);

		GD_URE_CreateUpdate_Profile_Utils::createupdateuser($user_id, $form_data);

		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES, $form_data['organizationtypes']);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES, $form_data['organizationcategories']);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_CONTACTPERSON, $form_data['contact_person'], true);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, $form_data['contact_number'], true);		
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_profileorganization;
// $gd_createupdate_profileorganization = new GD_CreateUpdate_ProfileOrganization();
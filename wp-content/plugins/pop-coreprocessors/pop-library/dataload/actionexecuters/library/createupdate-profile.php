<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Profile extends GD_CreateUpdate_User {

	protected function get_role() {

		return GD_ROLE_PROFILE;
	}

	protected function validatecontent(&$errors, $form_data) {

		parent::validatecontent($errors, $form_data);

		// Allow to validate the extra inputs
		$hooked_errors = apply_filters('gd_createupdate_profile:validatecontent', array(), $form_data);
		foreach ($hooked_errors as $error) {

			$errors[] = $error;
		}
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;

		$form_data = array_merge(
			$form_data,
			array(
				'short_description' => trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUP_SHORTDESCRIPTION)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUP_SHORTDESCRIPTION, $atts)),
				'display_email' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL, $atts),
				// 'locations' => $gd_template_processor_manager->get_processor(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP)->get_value(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts),
			)
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_createupdate_profile:form_data', $form_data, $atts);
		
		return $form_data;
	}

	protected function additionals($user_id, $form_data) {

		parent::additionals($user_id, $form_data);
		do_action('gd_createupdate_profile:additionals', $user_id, $form_data);	
	}
	protected function additionals_update($user_id, $form_data) {

		parent::additionals_update($user_id, $form_data);
		do_action('gd_createupdate_profile:additionals_update', $user_id, $form_data);
	}
	protected function additionals_create($user_id, $form_data) {

		parent::additionals_create($user_id, $form_data);

		// Needed for sending an email with the user newsletter subscription
		$extras = array(
			'display_name' => $this->get_display_name($form_data)
		);
		do_action('gd_createupdate_profile:additionals_create', $user_id, $form_data, $extras);
	}
	protected function createupdateuser($user_id, $form_data) {

		parent::createupdateuser($user_id, $form_data);

		// Last Edited: needed for the user thumbprint
		GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_LASTEDITED, POP_CONSTANT_CURRENTTIMESTAMP);

		GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, $form_data['display_email'], true);
		GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $form_data['short_description'], true);

		// Locations
		// GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_LOCATIONS, $form_data['locations']);	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_profile;
// $gd_createupdate_profile = new GD_CreateUpdate_Profile();
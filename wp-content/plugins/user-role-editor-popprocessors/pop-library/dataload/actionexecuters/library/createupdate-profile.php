<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_CreateUpdate_Profile extends GD_CreateUpdate_Profile {

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;
		
		$form_data = array_merge(
			$form_data,
			array(
				'communities' => $gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES, $atts),
			)
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_custom_createupdate_profile:form_data', $form_data, $atts);
		
		return $form_data;
	}	

	protected function additionals_create($user_id, $form_data) {

		parent::additionals_create($user_id, $form_data);
		do_action('gd_custom_createupdate_profile:additionals_create', $user_id, $form_data);
	}
	protected function additionals_update($user_id, $form_data) {

		parent::additionals_update($user_id, $form_data);
		do_action('gd_custom_createupdate_profile:additionals_update', $user_id, $form_data);
	}
	protected function additionals($user_id, $form_data) {

		parent::additionals($user_id, $form_data);
		do_action('gd_custom_createupdate_profile:additionals', $user_id, $form_data);	
	}
	protected function createuser(&$errors, $form_data) {

		$user_id = parent::createuser($errors, $form_data);

		$communities = $form_data['communities'];
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);
		
		// Set the privileges/tags for the new communities
		gd_ure_user_addnewcommunities($user_id, $communities);

		return $user_id;
	}
}
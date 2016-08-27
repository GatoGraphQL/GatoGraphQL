<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_ProfileOrganization extends GD_URE_CreateUpdate_Profile {

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;
		
		$form_data = array_merge(
			$form_data,
			array(
				'is_community' => trim(esc_attr($gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY, $atts)))
			)
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_createupdate_profileorganization:form_data', $form_data, $atts);
		
		return $form_data;
	}

	protected function additionals_create($user_id, $form_data) {

		parent::additionals_create($user_id, $form_data);
		do_action('gd_createupdate_profileorganization:additionals_create', $user_id, $form_data);
	}
	protected function additionals_update($user_id, $form_data) {

		parent::additionals_update($user_id, $form_data);
		do_action('gd_createupdate_profileorganization:additionals_update', $user_id, $form_data);
	}
	protected function additionals($user_id, $form_data) {

		parent::additionals($user_id, $form_data);		
		do_action('gd_createupdate_profileorganization:additionals', $user_id, $form_data);	
	}
	protected function createuser($errors, $form_data) {

		$user_id = parent::createuser($errors, $form_data);

		// Add the extra User Role
		$user = get_user_by('id', $user_id);
		$user->add_role(GD_URE_ROLE_ORGANIZATION);

		return $user_id;
	}
	protected function createupdateuser($user_id, $form_data) {

		parent::createupdateuser($user_id, $form_data);

		// Is community?
		$user = get_user_by('id', $user_id);
		if ($form_data['is_community']) {
			$user->add_role(GD_URE_ROLE_COMMUNITY);
		}
		else {
			$user->remove_role(GD_URE_ROLE_COMMUNITY);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_profileorganization;
// $gd_createupdate_profileorganization = new GD_CreateUpdate_ProfileOrganization();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_ProfileIndividual extends GD_URE_CreateUpdate_Profile {

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;
		
		$form_data = array_merge(
			$form_data,
			array(
				'last_name' => trim(esc_attr($gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_LASTNAME)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_LASTNAME, $atts)))
			)
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_createupdate_profileindividual:form_data', $form_data, $atts);
		
		return $form_data;
	}	

	protected function additionals_create($user_id, $form_data) {

		parent::additionals_create($user_id, $form_data);
		do_action('gd_createupdate_profileindividual:additionals_create', $user_id, $form_data);
	}
	protected function additionals_update($user_id, $form_data) {

		parent::additionals_update($user_id, $form_data);
		do_action('gd_createupdate_profileindividual:additionals_update', $user_id, $form_data);
	}
	protected function additionals($user_id, $form_data) {

		parent::additionals($user_id, $form_data);
		do_action('gd_createupdate_profileindividual:additionals', $user_id, $form_data);	
	}
	protected function createuser(&$errors, $form_data) {

		$user_id = parent::createuser($errors, $form_data);

		// Add the extra User Role
		$user = get_user_by('id', $user_id);
		$user->add_role(GD_URE_ROLE_INDIVIDUAL);
		
		return $user_id;
	}

	protected function get_display_name($form_data) {

		$first_name = $form_data['first_name'];
		$last_name = $form_data['last_name'];
		$user_login = $form_data['username'];
		
		if (!$first_name && !$last_name) 
			return $user_login;
			
		if (!$first_name) 
			return $last_name;
			
		if (!$last_name) 
			return $first_name;
			
		return sprintf("%s %s", $first_name, $last_name);
	}

	protected function get_updateuser_data($form_data) {

		$user_data = parent::get_updateuser_data($form_data);

		$user_data['last_name'] = $form_data['last_name'];

		return $user_data;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_profileindividual;
// $gd_createupdate_profileindividual = new GD_CreateUpdate_ProfileIndividual();
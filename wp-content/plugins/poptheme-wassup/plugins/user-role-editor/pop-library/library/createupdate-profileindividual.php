<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_CreateUpdate_ProfileIndividual extends GD_CreateUpdate_ProfileIndividual {

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;
		
		$form_data = array_merge(
			$form_data,
			GD_URE_CreateUpdate_Profile_Utils::get_form_data($atts),
			array(
				'individualinterests' => $gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS, $atts),
			)
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_custom_createupdate_profileindividual:form_data', $form_data, $atts);
		
		return $form_data;
	}	

	protected function additionals_create($user_id, $form_data) {

		parent::additionals_create($user_id, $form_data);
		do_action('gd_custom_createupdate_profileindividual:additionals_create', $user_id, $form_data);
	}
	protected function additionals_update($user_id, $form_data) {

		parent::additionals_update($user_id, $form_data);
		do_action('gd_custom_createupdate_profileindividual:additionals_update', $user_id, $form_data);
	}
	protected function additionals($user_id, $form_data) {

		parent::additionals($user_id, $form_data);
		do_action('gd_custom_createupdate_profileindividual:additionals', $user_id, $form_data);	
	}
	protected function createupdateuser($user_id, $form_data) {

		parent::createupdateuser($user_id, $form_data);

		GD_URE_CreateUpdate_Profile_Utils::createupdateuser($user_id, $form_data);
		
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $form_data['individualinterests']);	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_profileindividual;
// $gd_createupdate_profileindividual = new GD_CreateUpdate_ProfileIndividual();
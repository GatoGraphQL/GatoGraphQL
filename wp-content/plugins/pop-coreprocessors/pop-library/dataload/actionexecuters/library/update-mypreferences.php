<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UpdateMyPreferences {

	function execute(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);

		if ($errors) {
			return;
		}

		return $this->execute_update($errors, $form_data);
	}

	protected function get_inputs_metakeys() {

		// Allow URE to override here, adding "Joins communities"
		return apply_filters(
			'GD_UpdateMyPreferences:inputs-metakeys',
			array(
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
				// GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => GD_URE_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST,
				GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
				GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYCONTENT => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYCONTENT,
				GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS => GD_METAKEY_PROFILE_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS,
				GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNETWORKACTIVITY => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNETWORKACTIVITY,
				GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
			)
		);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;		

		$vars = GD_TemplateManager_Utils::get_vars();
		$user_id = $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/ ? $vars['global-state']['current-user-id']/*get_current_user_id()*/ : '';
		$form_data = array(
			'user_id' => $user_id,
		);

		// Add all the inputs values
		$form_data['inputs'] = array();
		$inputs = array_keys($this->get_inputs_metakeys());
		foreach ($inputs as $input) {
			$form_data['inputs'][$input] = $gd_template_processor_manager->get_processor($input)->get_value($input, $atts);
		}
		
		return $form_data;
	}	

	protected function validate(&$errors, &$form_data) {
		
	}

	protected function execute_update(&$errors, $form_data) {

		$user_id = $form_data['user_id'];
		
		$inputs_metakeys = $this->get_inputs_metakeys();

		// Comment Leo 22/03/2017: Disabled inputs: wait until implementation to have them enabled
		unset($inputs_metakeys[GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYCONTENT]);
		unset($inputs_metakeys[GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS]);
		unset($inputs_metakeys[GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNETWORKACTIVITY]);
		unset($inputs_metakeys[GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY]);

		// Save the values
		foreach ($inputs_metakeys as $input => $metakey) {
			GD_MetaManager::update_user_meta($user_id, $metakey, $form_data['inputs'][$input], true, true);
		}

		return true;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_updatemypreferences;
$gd_updatemypreferences = new GD_UpdateMyPreferences();

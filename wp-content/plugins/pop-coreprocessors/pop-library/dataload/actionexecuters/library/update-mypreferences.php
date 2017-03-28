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

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;		

		$user_id = is_user_logged_in() ? get_current_user_id() : '';
		$form_data = array(
			'user_id' => $user_id,
		);

		// Add all the inputs values
		$inputs = array(
			'emailnotifications-general-newpost' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
			'emailnotifications-general-specialpost' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST,
			'emailnotifications-network-createdpost' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST,
			'emailnotifications-network-recommendedpost' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
			'emailnotifications-network-followeduser' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
			'emailnotifications-network-subscribedtotopic' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
			'emailnotifications-network-addedcomment' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
			'emailnotifications-network-updownvotedpost' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
			'emailnotifications-subscribedtopic-createdpost' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST,
			'emailnotifications-subscribedtopic-addedcomment' => GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
			'emaildigests-dailynewcontent' => GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYCONTENT,
			'emaildigests-biweeklyupcomingevents' => GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS,
			'emaildigests-dailynetworkactivity' => GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNETWORKACTIVITY,
			'emaildigests-dailysubscribedtopicsactivity' => GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
		);
		foreach ($inputs as $key => $input) {
			$form_data[$key] = $gd_template_processor_manager->get_processor($input)->get_value($input, $atts);
		}
		
		return $form_data;
	}	

	protected function validate(&$errors, &$form_data) {
		
	}

	protected function execute_update(&$errors, $form_data) {

		$user_id = $form_data['user_id'];
		
		// Comment Leo 22/03/2017: Disabled inputs: wait until implementation to have them enabled
		$metakeys = array(
			'emailnotifications-general-newpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
			'emailnotifications-general-specialpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST,
			'emailnotifications-network-createdpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST,
			'emailnotifications-network-recommendedpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
			'emailnotifications-network-followeduser' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
			'emailnotifications-network-subscribedtotopic' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
			'emailnotifications-network-addedcomment' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
			'emailnotifications-network-updownvotedpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
			'emailnotifications-subscribedtopic-createdpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST,
			'emailnotifications-subscribedtopic-addedcomment' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
			// 'emaildigests-dailynewcontent' => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYCONTENT,
			// 'emaildigests-biweeklyupcomingevents' => GD_METAKEY_PROFILE_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS,
			// 'emaildigests-dailynetworkactivity' => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNETWORKACTIVITY,
			// 'emaildigests-dailysubscribedtopicsactivity' => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
		);

		// Save the values
		foreach ($metakeys as $key => $metakey) {
			GD_MetaManager::update_user_meta($user_id, $metakey, $form_data[$key], true, true);
		}

		return true;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_updatemypreferences;
$gd_updatemypreferences = new GD_UpdateMyPreferences();

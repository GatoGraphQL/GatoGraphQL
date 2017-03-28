<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Preferences
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_PreferencesHooks {

	function __construct() {

		add_filter(
			'PoPCore_Preferences:default:metakeys', 
			array($this, 'get_default_preferences_metakeys')
		);
	}

	function get_default_preferences_metakeys($metakeys) {

		// Give the default preferences for the theme
		return array_unique(array_merge(
			$metakeys,
			array(
				GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST,
				GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST,
				GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST,
				GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYCONTENT,
				GD_METAKEY_PROFILE_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS,
				GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNETWORKACTIVITY,
				GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
			)
		));
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_PreferencesHooks();
<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Preferences
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPCore_Preferences {

	function __construct() {

		add_action(
			'user_register', 
			array($this, 'grant_default_preferences'),
			10,
			1
		);
		add_action(
			'PoP:system-install', 
			array($this, 'system_install')
		);
	}

	function system_install() {

		// If a version was defined to grant the default preferences to all users (eg: because new default preferences have been added)
		// then execute it on system-install
		if (POP_COREPROCESSORS_PREFERENCES_GRANTDEFAULT_VERSION && POP_COREPROCESSORS_PREFERENCES_GRANTDEFAULT_VERSION == pop_version()) {

			// Get all users
			$query = array(
				'fields' => 'ID',
				'number' => 0,
				'role' => GD_ROLE_PROFILE,
			);
			$user_ids = get_users($query);

			// Grant them the privileges
			$this->grant_default_preferences_to_users($user_ids);
		}
	}

	/**
	 * Returns an array of metakeys with the names of the preferences to tick on
	 */
	protected function get_default_preferences_metakeys() {

		return apply_filters('PoPCore_Preferences:default:metakeys', array());
	}

	/**
	 * Give the default preferences to the user, after it is created
	 */
	function grant_default_preferences($user_id) {

		$this->grant_default_preferences_to_users(array($user_id));
	}

	/**
	 * Give the default preferences to the users
	 */
	function grant_default_preferences_to_users($user_ids) {

		$preferences_metakeys = $this->get_default_preferences_metakeys();
		foreach ($user_ids as $user_id) {
			foreach ($preferences_metakeys as $metakey) {

				// Save "true" for each preference metakey
				GD_MetaManager::update_user_meta($user_id, $metakey, true, true, true);
			}
		}
	}

	// /**
	//  * Give the default preferences to the users
	//  */
	// function grant_default_preferences_to_users($user_ids) {

	// 	$preferences_metakeys = $this->get_default_preferences_metakeys();
	// 	PoPCore_PreferencesUtils::grant_preferences($user_ids, $preferences_metakeys);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $popcore_preferences;
$popcore_preferences = new PoPCore_Preferences();
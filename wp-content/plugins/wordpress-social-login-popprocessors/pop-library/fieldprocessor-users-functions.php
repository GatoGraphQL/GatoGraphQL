<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Override the fieldprocessor-users functions for when the user is a WSL subscriber
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_WSL_FieldProcessor_Users_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {

		return array(GD_DATALOAD_FIELDPROCESSOR_USERS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$user = $resultitem;

		// Only if the user is a subscriber
		if (!user_has_profile_access($fieldprocessor->get_id($user))) {
			
			switch ($field) {

				case 'url' :
					
					// Return the user's website url from the connecting network (eg: facebook, twitter)
					return $user->user_url;
			}
		}

		return parent::get_value($user, $field, $fieldprocessor);
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_WSL_FieldProcessor_Users_Hook();
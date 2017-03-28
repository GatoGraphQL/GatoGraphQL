<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_Users_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_USERS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$user = $resultitem;

		switch ($field) {
			
			case 'followuser-url':
				return add_query_arg('uid', $fieldprocessor->get_id($user), get_permalink(POP_COREPROCESSORS_PAGE_FOLLOWUSER));

			case 'unfollowuser-url':
				return add_query_arg('uid', $fieldprocessor->get_id($user), get_permalink(POP_COREPROCESSORS_PAGE_UNFOLLOWUSER));

			case 'followers-count':
				return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);

			case 'short-description':
				return gd_get_user_shortdescription($fieldprocessor->get_id($user));

			case 'short-description-formatted' :
				
				// doing esc_html so that single quotes ("'") do not screw the map output
				$value = $fieldprocessor->get_value($user, 'short-description');
				return make_clickable(esc_html($value, $allowedposttags));

			case 'title':
				return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_METAKEY_PROFILE_TITLE, true);

			case 'display-email':
				return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_METAKEY_PROFILE_DISPLAYEMAIL, true);

			case 'display-email-string':
				// return $this->get_yesno_select_string($resultitem, 'display-email');
				return GD_DataLoad_FieldProcessor_Utils::get_boolvalue_string($fieldprocessor->get_value($resultitem, 'display-email'));

			// Override
			case 'contact':
			
				$value = array();
				// This one is a quasi copy/paste from the fieldprocessor
				if ($user_url = $fieldprocessor->get_value($user, 'user-url')) {
			
					$value[] = array(
						'tooltip' => __('Website', 'pop-coreprocessors'),
						'url' => maybe_add_http($user_url),
						'text' => $user_url,
						'target' => '_blank',
						'fontawesome' => 'fa-home',
					);
				}
				if ($fieldprocessor->get_value($user, 'display-email')) {
					if ($email = $fieldprocessor->get_value($user, 'email')) {
				
						$value[] = array(
							'fontawesome' => 'fa-envelope',
							'tooltip' => __('Email', 'pop-coreprocessors'),
							'url' => 'mailto:'.$email,
							'text' => $email
						);
					}
				}
				return $value;

			case 'is-profile':
				return is_profile($fieldprocessor->get_id($user));

			case 'locations':					
				return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_METAKEY_PROFILE_LOCATIONS);

			case 'has-locations':
				$locations = $fieldprocessor->get_value($user, 'locations');
				return !empty($locations);

			// User preferences
			case 'pref-emailnotif-general-newpost':
			case 'pref-emailnotif-general-specialpost':
			case 'pref-emailnotif-network-createdpost':
			case 'pref-emailnotif-network-recommendedpost':
			case 'pref-emailnotif-network-followeduser':
			case 'pref-emailnotif-network-subscribedtotopic':
			case 'pref-emailnotif-network-addedcomment':
			case 'pref-emailnotif-network-updownvotedpost':
			case 'pref-emailnotif-subscribedtopic-createdpost':
			case 'pref-emailnotif-subscribedtopic-addedcomment':
			case 'pref-emaildigests-dailynewcontent':
			case 'pref-emaildigests-biweeklyupcomingevents':
			case 'pref-emaildigests-dailynetworkactivity':
			case 'pref-emaildigests-dailysubscribedtopicsactivity':

				$metakeys = array(
					'pref-emailnotif-general-newpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
					'pref-emailnotif-general-specialpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST,
					'pref-emailnotif-network-createdpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST,
					'pref-emailnotif-network-recommendedpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
					'pref-emailnotif-network-followeduser' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
					'pref-emailnotif-network-subscribedtotopic' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
					'pref-emailnotif-network-addedcomment' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
					'pref-emailnotif-network-updownvotedpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
					'pref-emailnotif-subscribedtopic-createdpost' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST,
					'pref-emailnotif-subscribedtopic-addedcomment' => GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
					'pref-emaildigests-dailynewcontent' => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYCONTENT,
					'pref-emaildigests-biweeklyupcomingevents' => GD_METAKEY_PROFILE_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS,
					'pref-emaildigests-dailynetworkactivity' => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNETWORKACTIVITY,
					'pref-emaildigests-dailysubscribedtopicsactivity' => GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
				);
				return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), $metakeys[$field], true);
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Users_Hook();
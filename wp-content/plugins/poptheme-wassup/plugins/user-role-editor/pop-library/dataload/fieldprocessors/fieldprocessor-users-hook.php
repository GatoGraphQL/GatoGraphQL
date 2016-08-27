<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_URE_Custom_DataLoad_FieldProcessor_Users_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {

		return array(GD_DATALOAD_FIELDPROCESSOR_USERS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$user = $resultitem;

		switch ($field) {

			// case 'summary-tab-url':

			// 	// Summary Tab: for Communities still show only the Content from the Organization
			// 	$url = get_author_posts_url($fieldprocessor->get_id($user));
			// 	$value = GD_TemplateManager_Utils::add_tab($url, POP_COREPROCESSORS_PAGE_SUMMARY);
			// 	if (gd_ure_is_community($fieldprocessor->get_id($user))) {
			// 		$value = GD_URE_TemplateManager_Utils::add_source($value, GD_URE_URLPARAM_CONTENTSOURCE_ORGANIZATION);
			// 	}
			// 	return $value;
			
			case 'contact-person':

				if (gd_ure_is_organization($fieldprocessor->get_id($user))) {
					return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_CONTACTPERSON, true);
				}
				break;

			case 'contact-number':

				if (gd_ure_is_organization($fieldprocessor->get_id($user))) {
					return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_CONTACTNUMBER, true);
				}
				break;
			
			case 'organizationtypes':

				if (gd_ure_is_organization($fieldprocessor->get_id($user))) {
					return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES);
				}
				break;

			case 'organizationtypes-strings':	

				if (gd_ure_is_organization($fieldprocessor->get_id($user))) {			
					$selected = $fieldprocessor->get_value($user, 'organizationtypes');
					$params = array(
						'selected' => $selected
					);
					$organizationtypes = new GD_FormInput_OrganizationTypes($params);
					return $organizationtypes->get_selected_value();
				}
				break;
			
			case 'organizationcategories':		

				if (gd_ure_is_organization($fieldprocessor->get_id($user))) {	
					return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES);
				}
				break;

			case 'organizationcategories-strings':		

				if (gd_ure_is_organization($fieldprocessor->get_id($user))) {	
					$selected = $fieldprocessor->get_value($user, 'organizationcategories');
					$params = array(
						'selected' => $selected
					);
					$organizationcategories = new GD_FormInput_OrganizationCategories($params);
					return $organizationcategories->get_selected_value();
				}
				break;

			case 'has-organization-details':	

				if (gd_ure_is_organization($fieldprocessor->get_id($user))) {	
					if (
						$fieldprocessor->get_value($user, 'organizationtypes') ||
						$fieldprocessor->get_value($user, 'organizationcategories') ||
						$fieldprocessor->get_value($user, 'contact-person') ||
						$fieldprocessor->get_value($user, 'contact-number')
						) {
						return true;
					}
					return false;
				}
				break;

			// case 'lastname':
			// 	if (gd_ure_is_individual($fieldprocessor->get_id($user))) {
			// 		// $value = get_the_author_meta('user_lastname', $fieldprocessor->get_id($user));
			// 		return esc_attr($user->user_lastname);
			// 	}
			// 	break;	

			case 'individualinterests':

				if (gd_ure_is_individual($fieldprocessor->get_id($user))) {
					return GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS);
				}
				break;

			case 'individualinterests-strings':

				if (gd_ure_is_individual($fieldprocessor->get_id($user))) {			
					$selected = $fieldprocessor->get_value($user, 'individualinterests');
					$params = array(
						'selected' => $selected
					);
					$individualinterests = new GD_FormInput_IndividualInterests($params);
					return $individualinterests->get_selected_value();
				}
				break;

			case 'has-individual-details':		

				if (gd_ure_is_individual($fieldprocessor->get_id($user))) {	
					if ($fieldprocessor->get_value($user, 'individualinterests')) {
						return true;
					}
					return false;
				}
				break;																													
		}

		return parent::get_value($user, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_DataLoad_FieldProcessor_Users_Hook();
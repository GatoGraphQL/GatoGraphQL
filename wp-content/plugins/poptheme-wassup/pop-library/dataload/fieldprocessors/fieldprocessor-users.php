<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS', 'custom-users');
 
class GD_Custom_DataLoad_FieldProcessor_Users extends GD_DataLoad_FieldProcessor_Users {

	function get_name() {
	
		return GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS;
	}	

	function get_value($resultitem, $field) {
	
		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}
					
		$user = $resultitem;

		switch ($field) {

			// Override
			case 'contact':
			
				$value = parent::get_value($resultitem, $field);
				if ($facebook = $this->get_value($user, 'facebook')) {
			
					$value[] = array(
						'tooltip' => __('Facebook', 'poptheme-wassup'),
						'fontawesome' => 'fa-facebook',
						'url' => maybe_add_http($facebook),
						'text' => $facebook,
						'target' => '_blank'
					);
				}						
				if ($twitter = $this->get_value($user, 'twitter')) {
			
					$value[] = array(
						'tooltip' => __('Twitter', 'poptheme-wassup'),
						'fontawesome' => 'fa-twitter',
						'url' => maybe_add_http($twitter),
						'text' => $twitter,
						'target' => '_blank'
					);
				}	
				if ($linkedin = $this->get_value($user, 'linkedin')) {
			
					$value[] = array(
						'tooltip' => __('LinkedIn', 'poptheme-wassup'),
						'url' => maybe_add_http($linkedin),
						'text' => $linkedin,
						'target' => '_blank',
						'fontawesome' => 'fa-linkedin'
					);
				}	
				if ($youtube = $this->get_value($user, 'youtube')) {
			
					$value[] = array(
						'tooltip' => __('Youtube', 'poptheme-wassup'),
						'url' => maybe_add_http($youtube),
						'text' => $youtube,
						'target' => '_blank',
						'fontawesome' => 'fa-youtube',
					);
				}
				break;

			case 'facebook':

				$value = GD_MetaManager::get_user_meta($this->get_id($user), GD_METAKEY_PROFILE_FACEBOOK, true);
				break;

			case 'twitter':

				$value = GD_MetaManager::get_user_meta($this->get_id($user), GD_METAKEY_PROFILE_TWITTER, true);
				break;

			case 'linkedin':

				$value = GD_MetaManager::get_user_meta($this->get_id($user), GD_METAKEY_PROFILE_LINKEDIN, true);
				break;

			case 'youtube':

				$value = GD_MetaManager::get_user_meta($this->get_id($user), GD_METAKEY_PROFILE_YOUTUBE, true);
				break;

			// case 'allcontent-tab-url':

			// 	$value = $this->get_tab_page($user, POP_WPAPI_PAGE_ALLCONTENT);
			// 	break;

			// case 'summary-tab-url':

			// 	$value = $this->get_tab_page($user, POP_COREPROCESSORS_PAGE_SUMMARY);
			// 	break;

			// case 'projects-tab-url':

			// 	$value = $this->get_tab_page($user, POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS);
			// 	break;

			// case 'stories-tab-url':

			// 	$value = $this->get_tab_page($user, POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES);
			// 	break;

			// case 'announcements-tab-url':

			// 	$value = $this->get_tab_page($user, POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS);
			// 	break;

			// case 'discussions-tab-url':

			// 	$value = $this->get_tab_page($user, POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS);
			// 	break;
			
			default:

				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_FieldProcessor_Users();

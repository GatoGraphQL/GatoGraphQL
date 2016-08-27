<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_FIELDPROCESSOR_USERS', 'users');
 
class GD_DataLoad_FieldProcessor_Users extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_USERS;
	}

	protected function get_tab_page($user, $page) {
	
		$url = $this->get_value($user, 'url');
		return GD_TemplateManager_Utils::add_tab($url, $page);
	}
	
	function get_value($resultitem, $field) {
	
		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_USERS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}
					
		$user = $resultitem;
		switch ($field) {
		
			// case 'id' :
			// 	$value = $this->get_id($user);
			// 	break;

			case 'role' :
				$user_roles = $user->roles;

				// Allow to hook for URE: Make sure we always get the most specific role
				// Otherwise, users like Leo get role 'administrator'
			    $value = apply_filters('GD_DataLoad_FieldProcessor_Users:get_value:role', array_shift($user_roles), $user->ID);
				break;
			
			// Needed for tinyMCE-mention plug-in
			case 'mention-queryby' :
				$value = $this->get_value($user, 'display-name');
				break;
			
			case 'username' :
				$value = $user->user_login;
				break;

			case 'user-nicename' :
			case 'nicename' :
				$value = $user->user_nicename;
				break;

			case 'name' :				
			case 'display-name' :				
				$value = esc_attr($user->display_name);
				break;

			case 'firstname' :
				// $value = get_the_author_meta('user_firstname', $user->ID);
				$value = esc_attr($user->user_firstname);
				break;

			case 'lastname' :
				$value = esc_attr($user->user_lastname);
				break;	

			case 'email' :
				// $value = get_the_author_meta('user_email', $user->ID);
				$value = $user->user_email;
				break;
		
			case 'url' :

				$value = get_author_posts_url($user->ID);
				break;

			case 'description-formatted' :
				
				global $allowedposttags;
				$value = $this->get_value($user, 'description');
				$value = apply_filters('the_author_page_description', $value, $this->get_id($user));
				$value = make_clickable(wpautop(wp_kses($value, $allowedposttags)));
				break;

			case 'description' :
				
				$value = $user->description;
				break;

			case 'user-url' :
				
				$value = $user->user_url;
				break;

			case 'contact-small' :
				$value = array();
				$contacts = $this->get_value($user, 'contact');
				// Remove text, replace all icons with their shorter version
				foreach ($contacts as $contact) {
					$value[] = array(
						'tooltip' => $contact['tooltip'],
						'url' => $contact['url'],
						'fontawesome' => $contact['fontawesome']
					);
				}
				break;

			case 'contact' :

				$value = array();
				if ($user_url = $this->get_value($user, 'user-url')) {
			
					$value[] = array(
						'tooltip' => __('Website', 'pop-wpapi'),
						'url' => maybe_add_http($user_url),
						'text' => $user_url,
						'target' => '_blank',
						'fontawesome' => 'fa-home',
					);
				}
				break;

			case 'has-contact':
				
				$contact = $this->get_value($resultitem, 'contact');
				$value = !empty($contact);
				break;

			case 'excerpt' :
				$url = get_author_posts_url($user->ID);
				$readmore = sprintf(
					__('... <a href="%s">Read more</a>', 'pop-wpapi'),
					GD_TemplateManager_Utils::add_tab($url, POP_COREPROCESSORS_PAGE_DESCRIPTION)
				);
				$value = make_clickable(limit_string(strip_tags(wpautop(get_the_author_meta('description', $user->ID))), 300, $readmore));
				break;

			case 'userphoto' :			

				// $avatar = get_avatar($user->ID, 150);
				// $avatar_original = gd_user_avatar_original_file($avatar, $user->ID, 150);
				$userphoto = gd_get_useravatar_photoinfo($user->ID);
				$value = array(
					'src' => $userphoto['src'],
					'width' => $userphoto['width'],
					'height' => $userphoto['height']
				);
				break;

			case 'avatar' :			

				$value = gd_get_avatar($user->ID, 150);
				break;

			case 'contact-url' :
				$value = add_query_arg('uid', $this->get_id($user), get_permalink(POPTHEME_WASSUP_GF_PAGE_CONTACTUSER));
				break;
			
			default:

				// Check if it's an avatar
				$thumb_names = GD_Avatars_Manager_Factory::get_instance()->get_names();
				if (in_array($field, $thumb_names)) {

					$value = gd_get_avatar($user->ID, GD_Avatars_Manager_Factory::get_instance()->get_size($field));
					break;
				}

				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Users();

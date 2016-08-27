<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class WSL_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_NOTIFICATIONS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$notification = $resultitem;

		switch ($field) {

			case 'icon' :
				
				// URL depends basically on the action performed on the object type
				switch ($notification->object_type) {

					case 'User':

						switch ($notification->action) {

							case WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL:
								return gd_navigation_menu_item(POP_WPAPI_PAGE_EDITPROFILE, false);
						}
						break;
				}
				break;
			
			case 'url' :

				switch ($notification->object_type) {

					case 'User':

						switch ($notification->action) {

							// Link to the Edit Profile page
							case WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL:
								return get_permalink(POP_WPAPI_PAGE_EDITPROFILE);
						}
						break;
				}
				break;

			case 'message' :

				switch ($notification->object_type) {

					case 'User':

						switch ($notification->action) {

							case WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL:

								$user_id = $notification->object_id;
								return sprintf(
									__('<strong>Please update your email</strong><br/>%s does not provide your email, so we set a random one for you: <em>%s</em>. Please click here to change it to your real email, or you won\'t receive notifications from the %s website', 'wsl-pop'),
									// get_the_author_meta('display_name', $user_id),
									get_wsl_pop_provider($user_id),
									get_the_author_meta('user_email', $user_id),
									get_bloginfo('name')
								);
								
								// return $notification->object_name;
						}
						break;
				}
				break;
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new WSL_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook();
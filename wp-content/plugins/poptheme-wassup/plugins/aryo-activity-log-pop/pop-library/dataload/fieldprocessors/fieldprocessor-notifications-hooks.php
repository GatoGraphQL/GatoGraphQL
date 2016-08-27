<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_NOTIFICATIONS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$notification = $resultitem;

		switch ($field) {

			case 'icon' :
				
				// URL depends basically on the action performed on the object type
				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_REFERENCEDHIGHLIGHTPOST:
								return gd_navigation_menu_item(POPTHEME_WASSUP_PAGE_HIGHLIGHTS, false);
						}
						break;
				}
				break;
			
			case 'url' :

				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_REFERENCEDHIGHLIGHTPOST:

								// Can't point to the posted article since we don't have the information (object_id is the original, referenced post, not the referencing one),
								// so the best next thing is to point to the tab of all related content of the original post
								return GD_TemplateManager_Utils::add_tab(get_permalink($notification->object_id), POPTHEME_WASSUP_PAGE_HIGHLIGHTS);
						}
						break;
				}
				break;

			case 'message' :

				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_REFERENCEDHIGHLIGHTPOST:

								return sprintf(
									__('<strong>%s</strong> has made a highlight from <strong>%s</strong>', 'poptheme-wassup'),
									get_the_author_meta('display_name', $notification->user_id),
									get_the_title($notification->object_id)
								);						}
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
new PoPTheme_Wassup_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook extends GD_DataLoad_FieldProcessor_HookBase {

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

							case AAL_POP_ACTION_POST_REFERENCEDOPINIONATEDVOTEPOST:
								return gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, false);
						}
						break;
				}
				break;
			
			case 'url' :

				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_REFERENCEDOPINIONATEDVOTEPOST:

								// Can't point to the posted article since we don't have the information (object_id is the original, referenced post, not the referencing one),
								// so the best next thing is to point to the tab of all related content of the original post
								return GD_TemplateManager_Utils::add_tab(get_permalink($notification->object_id), POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES);
						}
						break;
				}
				break;

			case 'message' :

				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_REFERENCEDOPINIONATEDVOTEPOST:

								return sprintf(
									__('<strong>%1$s</strong> posted a %2$s after reading <strong>%3$s</strong>', 'poptheme-wassup-votingprocessors'),
									get_the_author_meta('display_name', $notification->user_id),
									gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES),
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
new VotingProcessors_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook();
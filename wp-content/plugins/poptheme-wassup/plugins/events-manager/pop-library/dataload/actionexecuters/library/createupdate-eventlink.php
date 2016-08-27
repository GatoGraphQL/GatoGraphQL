<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_EventLink extends GD_CreateUpdate_Event {

	protected function populate(&$EM_Event, $post_data) {

		// Add class "Link" on the event object
		if (!$EM_Event->get_categories()->categories[POPTHEME_WASSUP_EM_CAT_EVENTLINKS]) {
			$EM_Event->get_categories()->categories[POPTHEME_WASSUP_EM_CAT_EVENTLINKS] = new EM_Category(POPTHEME_WASSUP_EM_CAT_EVENTLINKS);
		}
		return parent::populate($EM_Event, $post_data);
	}


	/** --------------------------------------------------
	 * Function below was copied from class GD_CreateUpdate_WebPostLink 
	-------------------------------------------------- */
	protected function validatecontent(&$errors, $form_data) {

		parent::validatecontent($errors, $form_data);
		Wassup_CreateUpdate_Link_Utils::validatecontent($errors, $form_data);
	}
	
	/** --------------------------------------------------
	 * Function below was copied from class GD_CreateUpdate_WebPostLink 
	-------------------------------------------------- */
	protected function get_editor_input() {

		return GD_TEMPLATE_FORMCOMPONENT_LINK;
	}
}
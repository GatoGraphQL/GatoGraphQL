<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_AnnouncementLink extends GD_CreateUpdate_Announcement {

	protected function get_categories($form_data) {

		$ret = parent::get_categories($form_data);
		$ret[] = POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS;
		return $ret;
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
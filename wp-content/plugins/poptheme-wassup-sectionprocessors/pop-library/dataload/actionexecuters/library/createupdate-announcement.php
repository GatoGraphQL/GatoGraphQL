<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Announcement extends GD_CustomCreateUpdate_Post {

	protected function get_categories($form_data) {

		return array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS);
	}

	protected function volunteer() {

		return true;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_announcement;
// $gd_createupdate_announcement = new GD_CreateUpdate_Announcement();
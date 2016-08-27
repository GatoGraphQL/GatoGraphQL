<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Story extends GD_CustomCreateUpdate_Post {

	protected function get_categories($form_data) {

		return array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_story;
// $gd_createupdate_story = new GD_CreateUpdate_Story();
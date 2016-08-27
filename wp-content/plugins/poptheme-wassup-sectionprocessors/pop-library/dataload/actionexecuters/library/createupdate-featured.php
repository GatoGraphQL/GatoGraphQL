<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Featured extends GD_CustomCreateUpdate_Post {

	protected function get_categories($form_data) {

		return array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED);
	}
}
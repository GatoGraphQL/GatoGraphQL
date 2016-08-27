<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class GD_CreateUpdate_Highlight extends GD_CustomCreateUpdate_Post {
class GD_CreateUpdate_Highlight extends GD_CreateUpdate_UniqueReferenceBase {

	protected function get_categories($form_data) {

		return array(POPTHEME_WASSUP_CAT_HIGHLIGHTS);
	}

	protected function moderate() {

		return false;
	}

	protected function reference_mandatory() {

		return true;
	}

	protected function get_success_title($referenced = null) {

		if ($referenced) {

			return sprintf(
				__('Highlight from “%s”', 'poptheme-wassup'),
				$referenced->post_title
			);
		}

		return __('Highlight', 'poptheme-wassup');
	}
}
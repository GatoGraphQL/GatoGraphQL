<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_RecommendUnrecommendPost extends GD_UpdateUserMetaValue_Post {

	protected function eligible($post) {

		return apply_filters('GD_RecommendUnrecommendPost:eligible', true, $post);
	}

	/**
	 * Function to override
	 */
	protected function additionals($target_id, $form_data) {

		parent::additionals($target_id, $form_data);
		do_action('gd_recommendunrecommend_post', $target_id, $form_data);
	}
}
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UpvoteUndoUpvotePost extends GD_UpdateUserMetaValue_Post {

	protected function eligible($post) {

		return apply_filters('GD_UpdownvoteUndoUpdownvotePost:eligible', true, $post);
	}

	/**
	 * Function to override
	 */
	protected function additionals($target_id, $form_data) {

		parent::additionals($target_id, $form_data);
		do_action('gd_upvoteundoupvote_post', $target_id, $form_data);
	}
}
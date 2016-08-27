<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Utils {

	public static function moderate() {

		// Global constant defining if posts in the website can be created straight or subject to moderation
		return GD_CONF_CREATEUPDATEPOST_MODERATE;
	}

	public static function get_updatepost_status($status, $moderate) {

		$statuses = array('draft', 'publish');
		if ($moderate) {
			$statuses[] = 'pending';
		}

		// Status: Validate the value only is one of the following ones
		if (!in_array($status, $statuses)) {
			$status = 'draft';
		}
		
		// When moderating, if the status is publish, then do nothing (so it won't override the existing 'publish' status), and then it can't be hacked by passing this value in the $_POST
		if ($moderate && ($status == 'publish')) {
			return null;
		}

		return $status;
	}
	public static function get_createpost_status($status, $moderate) {

		$statuses = array('draft');
		if ($moderate) {
			
			// If moderating, cannot publish straight, goes to pending instead
			$statuses[] = 'pending';
		}
		else {

			// If not moderating, 2 values available: draft or publish
			$statuses[] = 'publish';
		}

		// Status: Validate the value only is one of the following ones
		if (!in_array($status, $statuses)) {
			$status = 'draft';
		}

		return $status;
	}

	public static function edit_post_link($link, $post_id) {

		if (!is_admin()) {

			$url = '';

			$post_type = get_post_type($post_id);
			if ($post_type == 'post') {

				// Change the URL depending on the category of the post
				if ($cat = gd_get_the_main_category($post_id)) {

					// Allow Custom to inject all the categories with their corresponding Edit Url Page
					$url = apply_filters('gd-createupdateutils:cat:edit-url', '', $cat, $post_id);
				}
			}
			else {

				// Change URL depending on post_type
				$url = apply_filters('gd-createupdateutils:post_type:edit-url', '', $post_type, $post_id);
			}

			if ($url) {
				$link = gd_get_nonce_url(GD_NONCE_EDITURL, $url, $post_id);
				$link = add_query_arg('pid', $post_id, $link);
			}
		}

		return $link;
	}
}
add_filter('get_edit_post_link', array('GD_CreateUpdate_Utils', 'edit_post_link'), 100, 2);

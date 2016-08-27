<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_AddComment {

	protected function validate(&$errors, $form_data) {

		if (empty($form_data['post_id'])) {
			$errors[] = __('We don\'t know what post the comment is for. Please reload the page and try again.', 'pop-wpapi');
		}

		// if (!is_user_logged_in()) {
		// 	$errors[] = sprintf(
		// 		__('Please %s to add a comment.', 'pop-wpapi'),
		// 		gd_get_login_html()
		// 	);
		// }

		if (empty($form_data['comment'])) {
			$errors[] = __('The comment is empty.', 'pop-wpapi');
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($comment_id, $form_data) {

		do_action('gd_addcomment', $comment_id, $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			// 'comment' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_COMMENT)->get_value(GD_TEMPLATE_FORMCOMPONENT_COMMENT, $atts),
			'comment' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR)->get_value(GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR, $atts),
			'parent' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_COMMENTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_COMMENTID, $atts),
			'post_id' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID, $atts)
		);
		
		return $form_data;
	}

	protected function get_comment_data($form_data) {

		$user_id = get_current_user_id();
		$author_url = gd_users_author_url($user_id);
		$comment_data = array(
			'user_id' => $user_id,
			'comment_author' => get_the_author_meta('display_name', $user_id),
			'comment_author_email' => get_the_author_meta('user_email', $user_id),
			'comment_author_url' => $author_url,
			'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
			'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
			'comment_content' => $form_data['comment'],
			'comment_parent' => $form_data['parent'],
			'comment_post_ID' => $form_data['post_id']
		);

		return $comment_data;
	}

	protected function execute($comment_data) {

		return wp_insert_comment($comment_data);
	}

	function addcomment(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);
		if ($errors) {
			return;
		}

		$comment_data = $this->get_comment_data($form_data);
		$comment_id = $this->execute($comment_data);

		// Allow for additional operations (eg: set Action categories)
		$this->additionals($comment_id, $form_data);

		return $comment_id;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_addcomment;
$gd_addcomment = new GD_AddComment();
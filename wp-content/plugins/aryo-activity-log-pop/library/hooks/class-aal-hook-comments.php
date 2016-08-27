<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_PoP_Hook_Comments extends AAL_Hook_Base {

	public function commented($comment_id, $form_data) {
		
		$this->log_comment($comment_id, $form_data['user_id'], AAL_POP_ACTION_COMMENT_ADDED);
	}

	public function spam_comment($comment_id) {
		
		// Enable if the current logged in user is the System Notification's defined user
		if (get_current_user_id() != POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS) {
			return;
		}

		$this->log_comment($comment_id, POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS, AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT);
	}

	protected function log_comment($comment_id, $user_id, $action) {
		
		$comment = get_comment($comment_id);
		aal_insert_log(
			array(
				'user_id' => $user_id,
				'action' => $action,
				'object_type' => 'Comments',
				'object_subtype' => get_post_type($comment->comment_post_ID),
				'object_id' => $comment_id,
				'object_name' => get_the_title($comment->comment_post_ID),
			)
		);

		// Comment Leo 09/03/2016: instead of logging 2 actions (added comment + replied to comment),
		// we only log create comment, and customize the message for the user if comment is a response to his own comment
		// Otherwise, it creates trouble since the same person may receives 2 notifications
		// // Is it a response to another comment?
		// if ($comment->comment_parent) {

		// 	aal_insert_log(
		// 		array(
		// 			'user_id' => $user_id,
		// 			'action' => AAL_POP_ACTION_COMMENT_REPLIED,
		// 			'object_type' => 'Comments',
		// 			'object_subtype' => get_post_type($comment->comment_post_ID),
		// 			'object_id' => $comment->comment_parent,
		// 			'object_name' => get_the_title($comment->comment_post_ID),
		// 		)
		// 	);	
		// }
	}

	public function delete_comment($comment_id) {

		AAL_Main::instance()->api->clear_comment($comment_id);
	}
	
	public function __construct() {

		// Commented
		add_action('gd_addcomment', array($this, 'commented'), 10, 2);

		// When a comment is deleted from the system, delete all notifications about that comment
		add_action('delete_comment', array($this, 'delete_comment'), 10, 1);

		// When a comment is marked as spam, tell the user about content guidelines
		add_action('spam_comment', array($this, 'spam_comment'), 10, 1);
		
		parent::__construct();
	}
	
}
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class AAL_PoP_Hook_Posts extends AAL_Hook_Base {

	public function created_post($post_id) {
		
		$post_status = get_post_status($post_id);
		if ($post_status == 'publish') {
			$this->log_created_post($post_id);
		}
		elseif ($post_status == 'pending') {

			$this->log_by_post_authors($post_id, AAL_POP_ACTION_POST_CREATEDPENDINGPOST);
		}
		elseif ($post_status == 'draft') {

			$this->log_by_post_authors($post_id, AAL_POP_ACTION_POST_CREATEDDRAFTPOST);
		}
	}

	public function updated_post($post_id, $atts, $log) {
		
		// Is it being created? (Eg: first created as draft, then "updated" to status publish)
		// Then trigger event Create, not Update
		// Simply check the previous status, if it was not published then trigger Create
		$post_status = get_post_status($post_id);
		if ($post_status == 'publish') {
			
			if ($log['previous-status'] != 'publish') {

				$this->log_created_post($post_id);
			}
			else {

				$this->log_by_post_authors($post_id, AAL_POP_ACTION_POST_UPDATEDPOST);
			}
		}
		elseif ($post_status == 'pending') {

			$this->log_by_post_authors($post_id, AAL_POP_ACTION_POST_UPDATEDPENDINGPOST);
		}
		elseif ($post_status == 'draft') {

			$this->log_by_post_authors($post_id, AAL_POP_ACTION_POST_UPDATEDDRAFTPOST);
		}
	}

	protected function log_created_post($post_id) {

		// Delete all previous notifications of "User created this post"
		// It might happen that the user has Published a post, then took it to draft, then published it again
		// So, we need to delete the first notification, or it will be repeated
		$clear_actions = array(
			AAL_POP_ACTION_POST_CREATEDPOST,
		);

		// Allow for co-authors
		$authors = gd_get_postauthors($post_id);
		foreach ($authors as $author) {

			AAL_Main::instance()->api->delete_post_notifications($author, $post_id, $clear_actions);
		// }
		}
		
		// Only after log the action
		$this->log_by_post_authors($post_id, AAL_POP_ACTION_POST_CREATEDPOST);	
	}

	public function removed_post($new_status, $old_status, $post) {

		if ($old_status == 'publish' && $new_status != 'publish') {

			$this->log_by_post_authors($post->ID, AAL_POP_ACTION_POST_REMOVEDPOST);
		}
	}

	protected function log_by_post_authors($post_id, $action) {
		
		$post = get_post($post_id);
		// if ($post->post_status == 'publish') {

		$post_title = get_the_title($post_id);

		// Allow for co-authors
		$authors = gd_get_postauthors($post_id);
		foreach ($authors as $author) {

			aal_insert_log(
				array(
					'user_id' => $author,
					'action' => $action,
					'object_type' => 'Post',
					'object_subtype' => $post->post_type,
					'object_id' => $post_id,
					'object_name' => $post_title,
				)
			);
		// }
		}
	}

	public function created_post_related_to_post($post_id) {
		
		if (get_post_status($post_id) == 'publish') {

			// Referenced posts: all of them for the new post
			$references = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_REFERENCES);
			$this->related_to_post($post_id, $references);
		}
	}

	public function updated_post_related_to_post($post_id, $atts, $log) {
		
		if (get_post_status($post_id) == 'publish') {

			// Referenced posts: if doing an update, pass only the newly added ones
			// If doing a create (changed "draft" to "publish"), then add all references
			if ($log['previous-status'] != 'publish') {

				// This is a Create post
				$references = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_REFERENCES);
			}
			else {
				
				// This is an Update post
				$references = $log['new-references'];
			}
			$this->related_to_post($post_id, $references);
		}
	}

	protected function related_to_post($post_id, $references) {
		
		// Referenced posts
		if ($references) {

			// $post_title = get_the_title($post_id);
			$post = get_post($post_id);
		
			// // Allow for co-authors
			// $authors = gd_get_postauthors($post_id);
			// foreach ($authors as $author) {

			// Allow to override the action, to be more specific on the related content being posted
			// Eg: Thoughts on TPP, Extracts, or just Related Content
			// This is needed so that the notification link goes to the appropriate tab, since it cannot point to the referencing post
			$action = apply_filters(
				'AAL_PoP_Hook_Posts:related_to_post:action',
				AAL_POP_ACTION_POST_REFERENCEDPOST,
				$post_id
			);
			foreach ($references as $reference_id) {
				aal_insert_log(
					array(
						'user_id' => $post->post_author, //$author,
						'action' => $action,
						'object_type' => 'Post',
						'object_subtype' => get_post_status($reference_id),
						'object_id' => $reference_id,
						'object_name' => get_the_title($reference_id),
						// 'object_name' => sprintf(
						// 	'%s has referenced your %s “%s”: <strong>%s</strong>',
						// 	get_the_author_meta('display_name', $author),
						// 	gd_get_postname($post_id, 'lc'),//strtolower(gd_get_postname($post_id)),
						// 	get_the_title($reference_id),
						// 	$post_title
						// ),
					)
				);
			}
			// }
		}
	}

	// public function commented_in_post($comment_id) {
		
	// 	$comment = get_comment($comment_id);
	// 	$this->log_post_action($comment->comment_post_ID, AAL_POP_ACTION_POST_COMMENTEDINPOST);
	// }

	public function recommended_post($post_id) {
		
		$this->log_post_action($post_id, AAL_POP_ACTION_POST_RECOMMENDEDPOST);
	}

	public function unrecommended_post($post_id) {
		
		$this->log_post_action($post_id, AAL_POP_ACTION_POST_UNRECOMMENDEDPOST);
	}

	public function upvoted_post($post_id) {
		
		$this->log_post_action($post_id, AAL_POP_ACTION_POST_UPVOTEDPOST);
	}

	public function undid_upvote_post($post_id) {
		
		$this->log_post_action($post_id, AAL_POP_ACTION_POST_UNDIDUPVOTEPOST);
	}

	public function downvoted_post($post_id) {
		
		$this->log_post_action($post_id, AAL_POP_ACTION_POST_DOWNVOTEDPOST);
	}

	public function undid_downvote_post($post_id) {
		
		$this->log_post_action($post_id, AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST);
	}

	public function admin_approval_post($new_status, $old_status, $post) {
		
		// Enable if the current logged in user is the System Notification's defined user
		if (get_current_user_id() != POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS) {
			return;
		}

		$action = null;
		if ($old_status == 'pending' && $new_status == 'publish') {

			$action = AAL_POP_ACTION_POST_APPROVEDPOST;
		}
		elseif (in_array($old_status, array('pending', 'publish')) && $new_status == 'draft') {

			$action = AAL_POP_ACTION_POST_DRAFTEDPOST;
		}
		elseif ($new_status == 'trash') {

			// Posts trashed, there are 2 different possibilities:
			// 1. the post was just trashed, because, for instance, the user posted it twice
			// 2. the post was trashed because it was spam
			// However, we don't have post status "spam", so to differentiate between these 2 states, we must change the way we trash posts
			// For #1:
			// 1st: mark the post as "draft"
			// 2nd: from there, delete it
			// For #2: just delete it straight from "publish"
			if (in_array($old_status, array('draft', 'pending'))) {

				$action = AAL_POP_ACTION_POST_TRASHEDPOST;
			}
			elseif (in_array($old_status, array('publish'))) {

				$action = AAL_POP_ACTION_POST_SPAMMEDPOST;
			}
		}
	
		if ($action) {

			// If any of these actions must be logged, then delete all previous admin_approval actions
			// This is needed for 2 reasons:
			// 1. Avoid duplicates (eg: pending to approval to pending to approval, just show the latest one will be enough)
			// 2. Stale state: a notification link saying "your post is approved" will point to the url, which might be invalid if later on the post was spammed
			$clear_actions = array(
				AAL_POP_ACTION_POST_APPROVEDPOST,
				AAL_POP_ACTION_POST_DRAFTEDPOST,
				AAL_POP_ACTION_POST_TRASHEDPOST,
				AAL_POP_ACTION_POST_SPAMMEDPOST,
			);
			AAL_Main::instance()->api->delete_post_notifications(POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS, $post->ID, $clear_actions);
			
			// Only after log the action
			$this->log_post_action($post->ID, $action, POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS);
		}
	}

	protected function log_post_action($post_id, $action, $user_id = null) {
		
		$post = get_post($post_id);	
		if (!$user_id) {
			$user_id = get_current_user_id();
		}

		aal_insert_log(
			array(
				'user_id' => $user_id,
				'action' => $action,
				'object_type' => 'Post',
				'object_subtype' => $post->post_type,
				'object_id' => $post_id,
				'object_name' => get_the_title($post_id),
			)
		);
	}
	
	public function __construct() {
		
		// Created/Updated/Removed Post
		add_action('gd_createupdate_post:create', array($this, 'created_post'));
		add_action('gd_createupdate_post:update', array($this, 'updated_post'), 10, 3);
		add_action('transition_post_status', array($this, 'removed_post'), 10, 3);

		// Admin approval
		if (is_admin()) {
			add_action('transition_post_status', array($this, 'admin_approval_post'), 10, 3);
		}

		// Referenced Post
		add_action('gd_createupdate_post:create', array($this, 'created_post_related_to_post'));
		add_action('gd_createupdate_post:update', array($this, 'updated_post_related_to_post'), 10, 3);

		// Commented in Post
		// Comment Leo 09/03/2016: action AAL_POP_ACTION_POST_COMMENTEDINPOST not needed, since we can achieve the same results only through action AAL_POP_ACTION_COMMENT_ADDED
		// add_action('gd_addcomment', array($this, 'commented_in_post'));

		// Recommended/Unrecommend/Upvote/Downvote Post
		add_action('gd_recommendpost', array($this, 'recommended_post'));
		add_action('gd_unrecommendpost', array($this, 'unrecommended_post'));
		add_action('gd_upvotepost', array($this, 'upvoted_post'));
		add_action('gd_undoupvotepost', array($this, 'undid_upvote_post'));
		add_action('gd_downvotepost', array($this, 'downvoted_post'));
		add_action('gd_undodownvotepost', array($this, 'undid_downvote_post'));
		
		
		parent::__construct();
	}

}
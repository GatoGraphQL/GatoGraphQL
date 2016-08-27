<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_FIELDPROCESSOR_COMMENTS', 'comments');

class GD_DataLoad_FieldProcessor_Comments extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_COMMENTS;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_COMMENTS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$comment = $resultitem;		
		
		switch ($field) {
		
			// case 'id' :
			// 	$value = $this->get_id($comment);
			// 	break;
			
			case 'content' :
				$value = gd_comments_apply_filters($comment->comment_content, true);
				break;

			case 'content-clipped' :
				$value = limit_string(strip_tags($this->get_value($resultitem, 'content')), 250);
				break;

			// case 'content-plain' :
			// 	$value = $comment->comment_content;
			// 	break;

			case 'author-name' :
				// Use the author information coming from the user_id, so it's always updated
				// $value = $comment->comment_author;
				$value = get_the_author_meta('display_name', $comment->user_id);
				break;

			case 'author-url' :
				// Use the author information coming from the user_id, so it's always updated
				// $value = $comment->comment_author_url;
				$value = gd_users_author_url($comment->user_id);
				break;

			case 'author-email' :
				// Use the author information coming from the user_id, so it's always updated
				// $value = $comment->comment_author_email;
				$value = get_the_author_meta('user_email', $comment->user_id);
				break;

			case 'author' :
				$value = $comment->user_id;
				break;

			case 'post-id' :
				$value = $comment->comment_post_ID;
				break;

			case 'approved' :
				$value = $comment->comment_approved;
				break;	

			case 'type' :
				$value = $comment->comment_type;
				break;	

			case 'parent' :
				$value = $comment->comment_parent;
				break;			

			case 'date' :
				$value = mysql2date( get_option('date_format'), $comment->comment_date_gmt );
				break;

			case 'replycomment-url' :
				$value = add_query_arg('pid', $this->get_value($resultitem, 'post-id'), get_permalink(POP_WPAPI_PAGE_ADDCOMMENT));
				$value = add_query_arg('cid', $this->get_id($comment), $value);
				break;	
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	

	function get_id($resultitem) {

		$comment = $resultitem;
	
		return $comment->comment_ID;		
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Comments(); 
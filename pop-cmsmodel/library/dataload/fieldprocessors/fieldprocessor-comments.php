<?php

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
	
    	$cmsresolver = PoP_CMS_ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
		$comment = $resultitem;		
		switch ($field) {
		
			case 'content' :
				$value = gd_comments_apply_filters($cmsresolver->get_comment_content($comment), true);
				break;

			case 'author-name' :
				$value = $cmsapi->get_the_author_meta('display_name', $cmsresolver->get_comment_user_id($comment));
				break;

			case 'author-url' :
				$value = $cmsapi->get_author_posts_url($cmsresolver->get_comment_user_id($comment));
				break;

			case 'author-email' :
				$value = $cmsapi->get_the_author_meta('user_email', $cmsresolver->get_comment_user_id($comment));
				break;

			case 'author' :
				$value = $cmsresolver->get_comment_user_id($comment);
				break;

			case 'post-id' :
				$value = $cmsresolver->get_comment_post_id($comment);
				break;

			case 'approved' :
				$value = $cmsresolver->get_comment_approved($comment);
				break;	

			case 'type' :
				$value = $cmsresolver->get_comment_type($comment);
				break;	

			case 'parent' :
				$value = $cmsresolver->get_comment_parent($comment);
				break;			

			case 'date' :
				$value = mysql2date($cmsapi->get_option('date_format'), $cmsresolver->get_comment_date_gmt($comment));
				break;
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	

	function get_id($resultitem) {

    	$cmsresolver = PoP_CMS_ObjectPropertyResolver_Factory::get_instance();
		$comment = $resultitem;	
		return $cmsresolver->get_comment_id($comment);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Comments(); 
<?php
namespace PoP\CMSModel;
 
define ('GD_DATALOAD_FIELDPROCESSOR_POSTS', 'posts');

class FieldProcessor_Posts extends \PoP\Engine\FieldProcessorBase {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_POSTS;
	}

	/**
	 * Overridable function.
	 * Needed for the Delegator, to cast an object from 'post' post_type to whichever corresponds ('attachment', 'event', etc)
	 */
	// function cast($post) {

	// 	return $post;
	// }
	
	function get_value($resultitem, $field) {
	
		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_POSTS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}		

    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$post = $resultitem;
		switch ($field) {

			// Mandatory: needed for the Multiple Layouts
			case 'post-type' :
				$value = $cmsresolver->get_post_type($post);
				break;

			case 'published' :
				$value = $cmsapi->get_post_status($this->get_id($post)) == 'publish';
				break;

			case 'not-published' :
				$value = !$this->get_value($post, 'published');
				break;

			case 'comments-url':
				return $this->get_value($post, 'url');

			case 'comments-count' :
				$value = $cmsapi->get_comments_number($this->get_id($post));
				break;

			case 'has-comments' :
				$value = $this->get_value($post, 'comments-count') > 0;
				break;

			case 'published-with-comments' :
				$value = $this->get_value($post, 'published') && $this->get_value($post, 'has-comments');
				break;

			case 'cat' :

				// Simply return the first category
				if ($cats = $this->get_value($post, 'cats')) {
					$value = $cats[0];
				}
				break;

			case 'cat-name' :
				
				if ($cat = $this->get_value($post, 'cat')) {

					$category = get_category($cat);
					$value = $category->name;
				}
				break;

			case 'cats' :
				$value = $cmsapi->wp_get_post_categories($this->get_id($post), array('fields' => 'ids'));
				if (!$value) {

					$value = array();
				}
				break;

			case 'cat-slugs' :
				$value = $cmsapi->wp_get_post_categories($this->get_id($post), array('fields' => 'slugs'));
				break;

			case 'tags' :
				$value = $cmsapi->wp_get_post_tags($this->get_id($post), array('fields' => 'ids'));
				break;

			case 'tag-names' :
				$value = $cmsapi->wp_get_post_tags($this->get_id($post), array('fields' => 'names'));
				break;

			case 'title' :
				$value = apply_filters('the_title', $cmsresolver->get_post_title($post), $this->get_id($post));
				break;

			case 'title-edit' :
				$value = $cmsresolver->get_post_title($post);
				break;
			
			case 'content' :
				$value = $cmsresolver->get_post_content($post);
				$value = apply_filters('pop_content_pre', $value, $this->get_id($post));
				$value = apply_filters('the_content', $value);
				$value = apply_filters('pop_content', $value, $this->get_id($post));
				break;

			case 'content-editor' : 
				$value = apply_filters('the_editor_content', $cmsresolver->get_post_content($post));
				break;

			case 'content-edit' : 
				$value = $cmsresolver->get_post_content($post);
				break;
		
			case 'url' :

				$value = $cmsapi->get_permalink($this->get_id($post));
				break;

			case 'excerpt' :
				$value = $cmsapi->get_the_excerpt($this->get_id($post));
				break;

			case 'comments' :
				$query = array(
					'status' => 'approve',
					'type' => 'comment', // Only comments, no trackbacks or pingbacks
					'post_id' => $this->get_id($post),
					// The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
					'order' =>  'ASC',
					'orderby' => 'comment_date_gmt',
				);
				$comments = $cmsapi->get_comments($query);
				$value = array();
				foreach ($comments as $comment) {
					$value[] = $cmsresolver->get_comment_id($comment);
				}
				break;

			case 'has-thumb' :			
				$value = $cmsapi->has_post_thumbnail($this->get_id($post));
				break;

			case 'featuredimage':				
				$value = $cmsapi->get_post_thumbnail_id($this->get_id($post));
				break;
	
			case 'author' :
				$value = $cmsresolver->get_post_author($post);
				break;	

			case 'status' :
				$value = $cmsapi->get_post_status($this->get_id($post));
				break;	

			case 'is-draft' :
				$status = $cmsapi->get_post_status($this->get_id($post));
				$value = ($status == 'draft');
				break;

			case 'date' :
				$value = mysql2date($cmsapi->get_option('date_format'), $cmsresolver->get_post_date($post));
				break;	

			case 'datetime' :
				// 15 Jul, 21:47
				$value = mysql2date('j M, H:i', $cmsresolver->get_post_date($post));
				break;	

			case 'edit-url' :
				$value = urldecode($cmsapi->get_edit_post_link($this->get_id($post)));
				break;	

			case 'delete-url' :
				$value = $cmsapi->get_delete_post_link($this->get_id($post));
				break;
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}

	function get_id($resultitem) {
	
    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
		$post = $resultitem;
		return $cmsresolver->get_post_id($post);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FieldProcessor_Posts();

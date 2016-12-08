<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOAD_FIELDPROCESSOR_POSTS', 'posts');

class GD_DataLoad_FieldProcessor_Posts extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_POSTS;
	}
	
	function get_thumb($post, $size = null, $add_description = false) {
			
		$thumb_id = gd_get_thumb_id($this->get_id($post));
		$img = $this->get_image_src($thumb_id, $size);	

		// Add the image description, for adding a disclaimer
		if ($add_description && $img) {
			
			$thumb = get_post($thumb_id);
			if ($description = $thumb->post_content) {
				// $img['description'] = make_clickable($description);
				$img['description'] = $description;
			}
		}

		return $img;
	}

	function get_image_src($imageid, $size = null) {
		
		$img = wp_get_attachment_image_src($imageid, $size);
		return array(
			'src' => $img[0],
			'width' => $img[1],
			'height' => $img[2]
		);
	}

	/**
	 * Overridable function.
	 * Needed for the Delegator, to cast an object from 'post' post_type to whichever corresponds ('attachment', 'event', etc)
	 */
	function cast($post) {

		return $post;
	}
	
	function get_value($resultitem, $field) {
	
		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_POSTS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}		

		$post = $resultitem;	

		switch ($field) {
		
			// case 'id' :
			// 	$value = $this->get_id($post);
			// 	break;

			// Mandatory: needed for the Multiple Layouts
			case 'post-type' :
				$value = $post->post_type;
				break;

			case 'published' :
				$value = get_post_status($this->get_id($post)) == 'publish';
				break;

			case 'not-published' :
				$value = !$this->get_value($post, 'published');
				break;

			case 'comments-url':
				return $this->get_value($post, 'url');

			case 'comments-count' :
				$value = get_comments_number($this->get_id($post));
				break;

			case 'has-comments' :
				$value = $this->get_value($post, 'comments-count') > 0;
				break;

			case 'published-with-comments' :
				$value = $this->get_value($post, 'published') && $this->get_value($post, 'has-comments');
				break;

			// Mandatory: needed for the Multiple Layouts
			case 'cat' :
				$value = gd_get_the_main_category($this->get_id($post));
				break;

			// // Mandatory: needed for the Multiple Layouts
			// case 'cats' :
			// 	$value = array();
			// 	$cats = get_the_category($this->get_id($post));
			// 	foreach ($cats as $cat) {
			// 		$value[] = $cat->term_id;
			// 	}

			// 	// Order them so there is a way to define the categories, to obtain the multilayout
			// 	array_multisort($value);
			// 	break;

			case 'cats' :
				$value = wp_get_post_categories($this->get_id($post), array('fields' => 'ids'));
				if (!$value) {
					$value = array();
				}
				break;

			// Needed for using handlebars helper "compare" to compare a category id in a buttongroup, which is taken as a string, inside a list of cats, which must then also be strings
			case 'cats-string' :
				$cats = $this->get_value($post, 'cats');
				$value = array();
				foreach ($cats as $cat) {
					$value[] = strval($cat);
				}
				break;

			case 'cat-slugs' :
				$value = wp_get_post_categories($this->get_id($post), array('fields' => 'slugs'));
				// $value = array();
				// $cats = get_the_category($this->get_id($post));
				// foreach ($cats as $cat) {
				// 	$value[] = $cat->slug;
				// }
				break;

			case 'tags' :
				$value = wp_get_post_tags($this->get_id($post), array('fields' => 'ids'));
				break;

			case 'tag-names' :
				$value = wp_get_post_tags($this->get_id($post), array('fields' => 'names'));
				break;

			case 'title' :
				//$value = get_the_title($post->ID);
				$value = apply_filters('the_title', $post->post_title, $this->get_id($post));
				break;

			case 'title-edit' :
				$value = $post->post_title;
				break;
			
			case 'content' :
				//$value = esc_attr(apply_filters('the_content', $post->post_content));
				$value = $post->post_content;
				$value = apply_filters('pop_content_pre', $value, $this->get_id($post));
				$value = apply_filters('the_content', $value);
				$value = apply_filters('pop_content', $value, $this->get_id($post));
				break;

			case 'content-editor' : 
				$value = apply_filters('the_editor_content', $post->post_content);
				break;

			case 'content-edit' : 
				// $value = apply_filters('the_editor_content', $post->post_content);
				$value = $post->post_content;
				break;
		
			case 'url' :

				// Only if already published
				// if (get_post_status($this->get_id($post)) == 'publish') {
				$value = get_permalink($post->ID);
				// }
				break;

			case 'excerpt' :
				$value = get_post_excerpt($post->ID);
				// $readmore = sprintf(
				// 	__('... <a href="%s">Read more</a>', 'pop-wpapi'),
				// 	get_permalink($this->get_id($post))
				// );
				// $value = empty($post->post_excerpt) ? limit_string(strip_tags(strip_shortcodes($post->post_content)), 500, $readmore) : $post->post_excerpt;
				// $value = make_clickable($value);
				break;

			case 'comments-lazy' : 
			case 'comments-lazy|maxheight' : 
			case 'noheadercomments-lazy' : 
				$value = array();
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
				$comments = get_comments($query);
				$value = array();
				foreach ($comments as $comment) {
					$value[] = $comment->comment_ID;
				}
				break;

			case 'thumb-thumbnail' :			
				$value = $this->get_thumb($post, 'thumbnail');
				break;
			case 'thumb-medium' :			
				$value = $this->get_thumb($post, 'medium');
				break;
			case 'thumb-large' :			
				$value = $this->get_thumb($post, 'large', true);
				break;
			case 'thumb-full' :			
				$value = $this->get_thumb($post, 'full', true);
				break;
			case 'thumb-full-src' :			
				$thumb = $this->get_value($post, 'thumb-full');
				$value = $thumb['src'];
				break;
			// case 'thumb-original' :			
			// 	$value = $this->get_thumb($post);
			// 	break;
			// case 'thumb-original-src' :			
			// 	$thumb = $this->get_value($post, 'thumb-original');
			// 	$value = $thumb['src'];
			// 	break;

			case 'has-thumb' :			
				$value = has_post_thumbnail($this->get_id($post));
				break;

			case 'featuredimage':				
				$value = get_post_thumbnail_id($this->get_id($post));
				break;

			case 'featuredimage-imgsrc':				
				$featuredimage = get_post_thumbnail_id($this->get_id($post));
				$value = array();
				if ($featuredimage) {
					$value = $this->get_image_src($featuredimage, 'medium');
				}
				break;
	
			case 'author' :
				$value = $post->post_author;
				break;	

			case 'status' :
				$value = get_post_status($this->get_id($post));
				break;	

			case 'is-draft' :
				$status = get_post_status($this->get_id($post));
				$value = ($status == 'draft');
				break;

			case 'date' :
				$value = mysql2date(get_option('date_format'), $post->post_date);
				break;	

			case 'datetime' :
				// 15 Jul, 21:47
				$value = mysql2date('j M, H:i', $post->post_date);
				break;	

			case 'edit-url' :
				$value = urldecode(get_edit_post_link($this->get_id($post)));
				break;	

			case 'delete-url' :
				$value = get_delete_post_link($this->get_id($post));
				break;	

			case 'addcomment-url' :
				$value = add_query_arg('pid', $this->get_id($post), get_permalink(POP_WPAPI_PAGE_ADDCOMMENT));
				break;

			case 'referenced-url' :

				// This can be called when the referenced post is unique. Eg: Add Highlight
				$references = GD_MetaManager::get_post_meta($this->get_id($post), GD_METAKEY_POST_REFERENCES);
				$value = get_permalink($references[0]);
				break;
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Posts();

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOAD_FIELDPROCESSOR_MEDIA', 'media');

class GD_DataLoad_FieldProcessor_Media extends GD_DataLoad_FieldProcessor_Posts {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_MEDIA;
	}

	// function cast($post) {

	// 	return wp_get_a($post->post_id);
	// }

	protected function get_thumb_id($attachment) {
			
		return $attachment->ID;
	}
	
	function get_post_mime_type_components($attachment) {

		if ( false !== strpos( $attachment->post_mime_type, '/' ) )
			list( $type, $subtype ) = explode( '/', $attachment->post_mime_type );
		else
			list( $type, $subtype ) = array( $attachment->post_mime_type, '' );		

		return array(
		
			'type' => $type,
			'subtype' => $subtype
		);
	}
	
	function get_embedurl_value($attachment) {

		// Allow to process the embed URL depending on the post_mime_type (eg: youtube video, google doc apps, etc)
		$value = $attachment->guid;
		
		$components = $this->get_post_mime_type_components($attachment);
		$type = $components['type'];

		if ($type == 'video') {
	
			// Modify the Youtube Embed URL (http://stackoverflow.com/a/8521287/1993198)
			$value = str_replace('watch?v=', 'embed/', $value);
		
			// Replace the first '&' with a '?' (since the first url param was removed)
			$value = preg_replace('/&/', '?', $value, 1);
		}		
		elseif ($type == 'application') {
	
			// Embed using Google Docs
			$value = add_query_arg('url', $value, GD_VIEWER_GOOGLEDOCS_URL);	
			$value = add_query_arg('embedded', 'true', $value);	
		}	

		return $value;
	}

	function get_url_value($attachment) {

		$components = $this->get_post_mime_type_components($attachment);
		$type = $components['type'];

		if ($type == 'video') {
	
			$value = $attachment->guid;
		}		
		else {

			$value = wp_get_attachment_url( $attachment->ID );
		}

		return $value;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_MEDIA, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$attachment = $resultitem;		
		
		switch ($field) {

			// Override
			case 'cat' :

				$cats = wp_get_post_terms($attachment->ID, gd_resources_media_taxonomy());
				if ($cat = $cats[0]) {
					$value = $cat->term_id;
				}
				break;

			// Override the url param to point to the original file
			case 'url' :
				$value = $this->get_url_value($attachment);
				break;
			
			// Needed for audio files
			case 'guid' :
				$value = $attachment->guid;
				break;
			
			// Needed for Youtube videos (will modify this value later through filter)
			case 'embedurl' :
				$value = $this->get_embedurl_value($attachment);
				break;	

			case 'post_mime_type' :
			
				$value = $this->get_post_mime_type_components($attachment);				
				break;
			
			case 'filename' :
				$value = wp_basename( $attachment->guid );
				break;
						
			case 'alt' :
				$value = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				break;				
			
			case 'description' :
				$value = $attachment->post_content;
				break;	

			case 'description-clickable' :
				$value = make_clickable(wpautop(trim($this->get_value($attachment, 'description'))));
				break;	
			
			case 'caption' :
				$value = $attachment->post_excerpt;
				break;	
			
			case 'name' :
				$value = $attachment->post_name;
				break;																			
			
			case 'icon' :
				//$value = array('url' => wp_mime_type_icon( $attachment->ID ), 'max-width' => $detail['max-width']);
				$value = array('src' => wp_mime_type_icon( $attachment->ID ));
				break;

			case 'source' :
				$value = get_post_meta($attachment->ID, GD_MEDIA_CUSTOM_FIELD_SOURCE, true);
				break;	

			case 'source-clickable' :
				$value = make_clickable($this->get_value($attachment, 'source'));
				break;	

			case 'resource' :

				$value = false;
				$categories = wp_get_object_terms( $attachment->ID, gd_resources_media_taxonomy() );
				$taxonomy_id = GD_CONF_MEDIA_CAT_RESOURCES;

				if ($categories) {
					foreach ($categories as $category) {
						if ($category->term_id == $taxonomy_id) {
							$value = true;
							break;
						}
					}
				}
				break;	

			case 'nonces' :
				// Taken from wp-includes/media.php
				if ( current_user_can( 'edit_post', $attachment->ID ) ) {
					$value = array('update' => wp_create_nonce( 'update-post_' . $attachment->ID ));
				}
				break;				
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;																											
		}

		return $value;
	}

	// function get_searchengine_crawlable_fields() {

	// 	$fields = parent::get_searchengine_crawlable_fields();
	
	// 	return array_merge(
	// 		$fields,
	// 		array(
	// 			'filename',
	// 			'alt',
	// 			'description',
	// 			'caption',
	// 			'name',
	// 			'source'
	// 		)
	// 	);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Media();

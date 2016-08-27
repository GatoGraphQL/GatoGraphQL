<?php

define ('GD_MEDIA_CUSTOM_FIELD_SOURCE', 'source');


/**
 * Add custom field
*/
add_filter( 'attachment_fields_to_edit', 'gd_media_attachment_fields', 10, 2 );
function gd_media_attachment_fields( $fields, $post ) {
 
    $meta = get_post_meta($post->ID, GD_MEDIA_CUSTOM_FIELD_SOURCE, true);
    $fields[GD_MEDIA_CUSTOM_FIELD_SOURCE] = array(
        'label' => __('Source', 'poptheme-wassup'),
        'input' => 'text',
        'value' => $meta,
        'show_in_edit' => true
    );
    return $fields;
}

/**
 * Update custom field on save
*/
add_filter( 'attachment_fields_to_save', 'gd_media_update_attachment_meta', 10, 2); 
function gd_media_update_attachment_meta($post, $attachment){
//    global $post;



	// Check the source of the data: includes/media.php or includes/ajax-actions.php
	// These 2 pass the data differently
	$id = $post->ID;
	if (!$id) {
		$id = $post['ID'];
	}
	$data = $attachment['attachments'][$id][GD_MEDIA_CUSTOM_FIELD_SOURCE];
	if (!$data) {
		$data = $attachment[GD_MEDIA_CUSTOM_FIELD_SOURCE];
	}	

    update_post_meta($id, GD_MEDIA_CUSTOM_FIELD_SOURCE, $data);
    return $post;
}
 
/**
 * Update custom field via ajax
*/
add_action('wp_ajax_save-attachment-compat', 'gd_media_media_xtra_fields', 0, 1);
function gd_media_media_xtra_fields() {
    $post_id = $_POST['id'];
    $meta = $_POST['attachments'][$post_id ][GD_MEDIA_CUSTOM_FIELD_SOURCE];
    update_post_meta($post_id , GD_MEDIA_CUSTOM_FIELD_SOURCE, $meta);
    clean_post_cache($post_id);
}


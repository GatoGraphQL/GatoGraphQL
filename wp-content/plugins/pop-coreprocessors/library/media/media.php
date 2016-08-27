<?php


add_filter('icon_dirs', 'gd_media_icon_dirs', 100);

// Icons copied from EG-Attachment plugin
function gd_media_icon_dirs($args) {

	// If $args is not an array => return directly the value
	if (!is_array($args)) return $args ;

	// Add the icons path of the Resources
	$media_icons = array(POP_COREPROCESSORS_LIB.'/media/img/flags' => POP_COREPROCESSORS_URI_LIB.'/media/img/flags');

	return array_merge($media_icons, $args);
}


/**---------------------------------------------------------------------------------------------------------------
 *
 * Media Manager
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Only allow editors and admins to see all Media, all other users only see own files
add_filter('gd_wp_ajax_query_attachments', 'gd_resources_add_author_to_query');
function gd_resources_add_author_to_query($query) { 
	
	// Only allowed users (admin, editors) can edit other's files. If the user does not have this role, limit the retrieved files to his own's
	//if (!current_user_can(GD_CAPABILITY_EDIT_OTHERS_FILES)) {
	if (!user_has_admin_access()) {
		$query['author'] = get_current_user_id();
	}
	
	return $query; 
}

/**---------------------------------------------------------------------------------------------------------------
 * Do not allow certain filetypes
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('upload_mimes','gd_upload_mimes');
function gd_upload_mimes( $mime ) {
    $unset = array('exe', 'gz', 'gzip', 'rar', 'tar', 'zip');

    foreach ($unset as $val) {
        unset( $mime[$val] );
    }

    return $mime;
}

/**---------------------------------------------------------------------------------------------------------------
 * Set default thumb size from 'medium' to 'large'
 * ---------------------------------------------------------------------------------------------------------------*/

add_action('PoP:install', 'gd_media_update_image_default_size');
function gd_media_update_image_default_size() {

	update_option('image_default_size', 'large');
}

/**---------------------------------------------------------------------------------------------------------------
 * Embed size (for Youtube videos): make it responsive
 * Source: http://alxmedia.se/code/2013/10/make-wordpress-default-video-embeds-responsive/
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter( 'embed_defaults', 'gd_embed_defaults_size' );
function gd_embed_defaults_size()
{
    // adjust these pixel values to your needs
    return array( 'width' => 640, 'height' => 480 );
}


/**---------------------------------------------------------------------------------------------------------------
 * Allow for prettyPhoto to override its rel here
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_image_rel() {

	return apply_filters('gd_image_rel', '');
}


/**---------------------------------------------------------------------------------------------------------------
 * Implementation JW Player
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('gd_jwp6_hide_insert_into_post_button_urls', 'gd_jwp6_hide_insert_into_post_button_urls_impl');
// function gd_jwp6_hide_insert_into_post_button_urls_impl($urls) {

// 	$urls[] = get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYRESOURCES);
// 	return $urls;
// }


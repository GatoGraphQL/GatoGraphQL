<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * WP Super Cache Plugin functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// In function wp_cache_phase2() in file wp-super-cache/wp-cache-phase2.php it doesn't delete the cache when transitioning a post from publish to draft, do it here then
add_action('publish_to_draft', 'gd_wpsc_publish_to_draft', 0);
function gd_wpsc_publish_to_draft($post_id) {
	define ('WPSCFORCEUPDATE', true); // Added so that it also deletes the draft, only when transitioning, not always
	wp_cache_post_edit($post_id);
}

// Whenever a user is created or updated
add_action('gd_createupdate_user:additionals', 'gd_wp_cache_user_edit', 0, 0);

// When updating My Communities
add_action('gd_update_mycommunities:update', 'gd_wp_cache_user_edit', 0, 0);

// When updating a user membership
add_action('GD_EditMembership:update', 'gd_wp_cache_user_edit', 0, 0);
function gd_wp_cache_user_edit() {

	// Delete the cache when the information is updated
	// Copied from plugins/wp-super-cache/wp-cache-phase2.php function wp_cache_post_edit($post_id)

	// Only do it if the Cache is enabled
	global $cache_enabled;
	if (!$cache_enabled) return;
	
	global $blog_cache_dir;
	if ( $wp_cache_object_cache ) {
		reset_oc_version();
	} else {
		prune_super_cache( $blog_cache_dir, true );
		prune_super_cache( get_supercache_dir(), true );
	}
}

// Original code: from http://wordpress.stackexchange.com/questions/98526/empty-super-cache-programmatically-with-acf-action
// Delete all files under folder saved in $_POST['cachepath'] or in param $cachepath (it is array, to delete many folders)
// add_action('gd_cache_flush', 'gd_supercache_delete_cache', 10, 2);
// function gd_supercache_delete_cache($paths, $deletemeta = false) {

// 	// Only do it if the Cache is enabled
// 	global $cache_enabled;
// 	if ($cache_enabled) {
// 		foreach ($paths as $path) {
			
// 			// Remove the leading '/'
// 			if (substr($path, 0, 1) == '/') {
// 				$path = substr($path, 1);
// 			}
			
// 			$path = trailingslashit(get_supercache_dir()) . $path;
// 			$files = get_all_supercache_filenames( $path );
// 			foreach( $files as $cache_file ) {

// 				$file_path = $path . $cache_file;
// 				prune_super_cache( $file_path, true ); 
// 			}
// 		}
		
// 		if ($deletemeta) {
// 			// Also delete all meta = cached pages with params (?x=y)
// 			global $blog_cache_dir;	
// 			wp_cache_clean_legacy_files( $blog_cache_dir, null );	
// 		}
// 	}
// }

/**
 * Cache the JSON response (starts with { and finishes with } or starts with [ and finishes with ])
 */
// Do not print the $buffer
global $wp_super_cache_comments;
$wp_super_cache_comments = false;
add_filter('wp_cache_eof_tags', 'gd_wp_cache_eof_tags_json_response');
function gd_wp_cache_eof_tags_json_response() {

	return '/(}|])/i';
}

/**---------------------------------------------------------------------------------------------------------------
 * Whenever there is a new software version, delete the cache
 * ---------------------------------------------------------------------------------------------------------------*/
// Priority 100: execute after the GD_Template_CustomSettingsProcessor gets initialized
add_action('PoP:install', 'gd_wp_cache_deletecache');
function gd_wp_cache_deletecache() {

	wp_cache_clear_cache();
}


/**---------------------------------------------------------------------------------------------------------------
 * Allow to override what files to ignore to cache: all the ones with checkpoint needed
 * ---------------------------------------------------------------------------------------------------------------*/
// Priority 100: execute after the GD_Template_CustomSettingsProcessor gets initialized
add_action('PoP:install', 'gd_wp_cache_set_rejected_uri');
function gd_wp_cache_set_rejected_uri() {

	// Check if we have rejected uris, if so replace them in wp-cache-config.php
	$rejected_uris = apply_filters('gd_wp_cache_set_rejected_uri', array());
	if ($rejected_uris) {

		// Add the original ones in
		$original = array('wp-.*\\\\.php', 'index\\\\.php');
		$rejected_uris = array_merge(
			$original,
			$rejected_uris
		);
		$pop_cache_rejected_uri = "array('".implode("', '", $rejected_uris)."')";
		
		// Taken from http://z9.io/wp-super-cache-developers/
		global $wp_cache_config_file;
		wp_cache_replace_line('^ *\$cache_rejected_uri', "\$cache_rejected_uri = " . $pop_cache_rejected_uri . ";", $wp_cache_config_file);
	}
}
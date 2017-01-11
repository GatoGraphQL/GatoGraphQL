<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * WP Super Cache Plugin functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Remove all cache deleting when a comment is added or updated. That is because the property "Only refresh current page when comments made."
// doesn't work, because the logic reads:
// if ( isset( $wp_cache_refresh_single_only ) && $wp_cache_refresh_single_only && ( strpos( $_SERVER[ 'HTTP_REFERER' ], 'edit-comments.php' ) || strpos( $_SERVER[ 'REQUEST_URI' ], 'wp-comments-post.php' ) ) ) {
// and since we're not coming from wp-comments-post.php then this logic never works, and for every comment added the whole cache is deleted
// This theme can operate without deleting the cache, since comments are retrieved on a 2nd request, which is never cached to start with
add_action( 'init', 'pop_wpsc_remove_comment_actions' );
function pop_wpsc_remove_comment_actions() {
	remove_action('trackback_post', 'wp_cache_get_postid_from_comment', 99);
	remove_action('pingback_post', 'wp_cache_get_postid_from_comment', 99);
	remove_action('comment_post', 'wp_cache_get_postid_from_comment', 99);
	remove_action('edit_comment', 'wp_cache_get_postid_from_comment', 99);
	remove_action('wp_set_comment_status', 'wp_cache_get_postid_from_comment', 99, 2);
}

function pop_wpsc_is_stacktrace_coming_from_comments() {

	// Stack trace:
	// array(16) {
	//   [0]=>array {
	//     ["file"]=>"wp-content/plugins/poptheme-wassup/plugins/wp-super-cache/library/functions.php"
	//     ["function"]=>"pop_wpsc_is_stacktrace_coming_from_comments"
	//   }
	//   [1]=>array {
	//     ["file"]=>"wp-includes/plugin.php"
	//     ["function"]=>"pop_wpsc_removeforcomments"
	//   }
	//   [2]=>array {
	//     ["file"]=>"wp-includes/post.php"
	//     ["function"]=>"do_action"
	//   }
	//   [3]=>array {
	//     ["file"]=>"wp-includes/comment.php"
	//     ["function"]=>"clean_post_cache"
	//   }
	//   [4]=>array {
	//     ["file"]=>"wp-includes/comment.php"
	//     ["function"]=>"wp_update_comment_count_now"
	//   }
	//   ...
	// }

	$stack = debug_backtrace();
	return ($stack[4] && $stack[4]['function'] == 'wp_update_comment_count_now');
}

// Priority 9: execute before add_action( 'clean_post_cache', 'wp_cache_post_edit' );
add_action('clean_post_cache', 'pop_wpsc_removeforcomments', 9, 1);
function pop_wpsc_removeforcomments($post_id) {
	
	if (pop_wpsc_is_stacktrace_coming_from_comments()) {
	
		// This one must also be removed because it's called from function wp_update_comment_count_now($post_id) in comment.php
		// Check if this function is on the stack, then remove the WP Super Cache hook
		remove_action( 'clean_post_cache', 'wp_cache_post_edit' );

		// But still remove all the /loaders/posts cached pages, since that's where the comments are brought
		pop_wpsc_deletecommentscache($post_id);
	}
}
function pop_wpsc_deletecommentscache($post_id) {
	
	// if (POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS) {
		
	// Code copied from function wp_cache_phase2_clean_cache($file_prefix)
	global $cache_path, $blog_cache_dir, $file_prefix, $blog_id;
	
	if( !wp_cache_writers_entry() )
		return false;
	
	// Comment Leo 11/01/2017: do not check the path, only the parameters, so that any page that has pid=$post_id gets deleted
	// For sure there are 2 such pages: POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS and POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS
	// Check that the path is in the cache URI
	// $path = GD_TemplateManager_Utils::get_page_path(POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS);

	// Check that the param 'pid' with the right post_id is in the URI
	// It can be of the shape pid=$post_id, pid[]=$post_id, or pid[3]=$post_id
	// Example of hit: 
	// "uri":"localhost\/en\/add-comment\/?pid=23787&target=addons&module=settingsdata&output=json&theme=wassup&thememode=sliding&themestyle=swift&settingsformat="
	// Explanation of the regex:
		// These 2 below wouldn't work! So it's not done:
		// Such regex that didn't work: $regex = '/"uri":"(.*)[\?|&]'.'pid'.'(\[[0-9]+\])?='.$post_id.'(&|")/';
		// - search in field "uri":"
		// - then anything
	// - before 'pid' there's either ? or &
	// - after 'pid' can have =, []=, or [number]=
	// - then must have $post_id
	// - then, it must either have & or be the end
	$regex = '/[\?|&]'.'pid'.'(\[[0-9]+\])?='.$post_id.'(&|")/';

	// wp_cache_debug( "wp_cache_phase2_clean_cache: Cleaning cache in $blog_cache_dir" );
	if ( $handle = @opendir( $blog_cache_dir ) ) { 
		while ( false !== ($file = @readdir($handle))) {
			if ( strpos( $file, $file_prefix ) !== false ) {
				if ( strpos( $file, '.html' ) ) {
					// delete old legacy files immediately
					// wp_cache_debug( "wp_cache_phase2_clean_cache: Deleting obsolete legacy cache+meta files: $file" );
					// @unlink( $blog_cache_dir . $file);
					// @unlink( $blog_cache_dir . 'meta/' . str_replace( '.html', '.meta', $file ) );
				} else {
					$meta = json_decode( wp_cache_get_legacy_cache( $blog_cache_dir . 'meta/' . $file ), true );
					if ( $meta[ 'blog_id' ] == /*$wpdb->blogid*/$blog_id && /*strpos($meta['uri'], $path) !== false && */preg_match($regex, $meta['uri']) ) {
						wp_cache_debug( "pop_wpsc_removeforcomments: Deleting file: $file with uri " . $meta['uri'] );
						@unlink( $blog_cache_dir . $file );
						@unlink( $blog_cache_dir . 'meta/' . $file );
					}
				}
			}
		}
		closedir($handle);
	}
	wp_cache_writers_exit();
	// }
}
// Comment Leo 10/01/2017: we can't add it again, since it will then be executed, even if adding after the supposed priority is gone past
// // Priority 11: it can't call wp_cache_post_edit on priority 10 anymore during the current execution
// add_action('clean_post_cache', 'pop_wpsc_readdforcomments', 11);
// function pop_wpsc_readdforcomments() {

// 	if (pop_wpsc_is_stacktrace_coming_from_comments()) {

// 		// Add again
// 		add_action( 'clean_post_cache', 'wp_cache_post_edit' );
// 	}
// }

/**---------------------------------------------------------------------------------------------------------------
 * WP Super Cache Plugin functions
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

// Comment Leo 11/01/2017: this one is not needed, since we're not showing this number anywhere, so no need to delete the cache
// add_action('gd_followuser', 'gd_wp_cache_user_edit', 0, 0);
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

	// }|]: JSON file can also be cached
	return '/(<\/html>|<\/rss>|<\/feed>|<\/urlset|<\?xml|}|])/i';
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
// Priority 20: After the config file has been created
add_action('PoP:system-install', 'gd_wp_cache_set_rejected_uri', 20);
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
		
		// // Taken from http://z9.io/wp-super-cache-developers/
		// global $wp_cache_config_file;
		// wp_cache_replace_line('^ *\$cache_rejected_uri', "\$cache_rejected_uri = " . $pop_cache_rejected_uri . ";", $wp_cache_config_file);

		// Do it on the pop cache config file instead, so that different websites can keep different configurations
		global $pop_wp_cache_config_file;
		wp_cache_replace_line('^ *\$cache_rejected_uri', "\$cache_rejected_uri = " . $pop_cache_rejected_uri . ";", $pop_wp_cache_config_file);
	}
}
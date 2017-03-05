<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Public Post Preview plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd-createupdate-post:execute:successstring', 'gd_ppp_createupdate_add_preview_link', 10, 3);
function gd_ppp_createupdate_add_preview_link($success_string, $post_id, $status) {
	if (in_array($status, array('draft', 'pending'))) {
				
		$previewurl = gd_ppp_preview_link($post_id);

		// Allow to inject data-sw-networkfirst="true"
		$previewurl_params = apply_filters('gd_ppp_previewurl_link_params', '');
		if ($previewurl) {
			$success_string .= sprintf(
				' <a href="%1$s" target="%2$s" class="btn btn-xs btn-primary" %4$s><i class="fa fa-fw fa-eye"></i>%3$s</a>', 
				$previewurl, 
				GD_URLPARAM_TARGET_QUICKVIEW,
				__('Preview', 'poptheme-wassup'),
				$previewurl_params
			);
		}
	}

	return $success_string;
}

function gd_ppp_preview_link($post_id) {

	// Check if preview enabled for this post
	$preview_post_ids = DS_Public_Post_Preview::get_preview_post_ids();
	if(in_array( $post_id, $preview_post_ids )) {

		return DS_Public_Post_Preview::get_preview_link( get_post($post_id) );
	}
	
	return null;
}

add_filter('gd_createupdate_post', 'gd_ppp_add_public_preview', 10, 1);
function gd_ppp_add_public_preview($post_id) {

	$post_status = get_post_status($post_id);
	if (in_array($post_status, array('draft', 'pending', 'publish'))) {

		$preview_post_ids = DS_Public_Post_Preview::get_preview_post_ids();
		// Add the post to have "public preview"
		if (in_array($post_status, array('draft', 'pending')) && !in_array($post_id, $preview_post_ids)) {

			$preview_post_ids[] = $post_id;
			DS_Public_Post_Preview::set_preview_post_ids( $preview_post_ids );
		}
		// Remove it, so published posts don't have the "public preview" enabled anymore
		elseif (($post_status == 'publish') && in_array($post_id, $preview_post_ids)) {

			array_splice($preview_post_ids, array_search($post_id, $preview_post_ids), 1);
			DS_Public_Post_Preview::set_preview_post_ids( $preview_post_ids );
		}
	}
}


// Complement to the plugin: also save_post when in frontend
if ( ! is_admin() ) {
	add_action( 'save_post', array( 'DS_Public_Post_Preview', 'register_public_preview' ), 20, 2 );
}

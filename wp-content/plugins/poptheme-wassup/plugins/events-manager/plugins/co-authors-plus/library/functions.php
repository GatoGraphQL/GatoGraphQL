<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Co Authors Plus plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Add Co-authors
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter( 'coauthors_supported_post_types', 'gd_em_return_event_posttype' );
function gd_user_event_post_ids($authors = null) {

	// Allow to specify for which users (used by Events Map profile filtering)
	if (is_null($authors)) {
		$vars = GD_TemplateManager_Utils::get_vars();
		$authors = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
	}
	elseif (is_array($authors)) {
		$authors = implode(',', $authors);
	}

	$post_id = array();
	$user_args = array(
		'nopaging' => true,
		'post_type' => EM_POST_TYPE_EVENT,
		'author' => $authors,
		'post_status' => array('draft', 'future', 'pending', 'publish')
	);
	$user_posts = new WP_Query( $user_args );
	while( $user_posts->have_posts() ):
		$user_posts->next_post();
		$post_id[] = $user_posts->post->ID;
	endwhile;

	return $post_id;
}
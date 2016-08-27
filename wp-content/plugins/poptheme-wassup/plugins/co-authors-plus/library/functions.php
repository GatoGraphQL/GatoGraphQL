<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Co-Authors Plus Plugin functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * No need from any avatar size from this plugin
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('coauthors_guest_author_avatar_sizes', 'empty_array', 10000);
function empty_array($anything) {
	return array();
}

/**---------------------------------------------------------------------------------------------------------------
 * Override function to get the authors of a post
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_postauthors', 'gd_gd_get_postauthors', 10, 2);
function gd_gd_get_postauthors($authors, $post) {
	$coauthors = get_coauthors($post->ID);
	$coauthors_ids = array_map('gd_get_the_id', $coauthors);

	return $coauthors_ids;
}


add_action('gd_createupdate_post', 'gd_cap_sharewithprofiles', 10, 2);
function gd_cap_sharewithprofiles($post_id, $atts) {

	global $coauthors_plus, $userdata, $gd_template_processor_manager;
		
	// Was the Share With Profiles field added to the form?
	if ($sharewithprofiles = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS)->get_value(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS, $atts)) {
		
		$coauthors = array();
		foreach ($sharewithprofiles as $userid) {
			$user = get_user_by('id', $userid);
			$coauthors[] = $user->user_nicename;
		}

		// If the current user is not there, add it (in first position)
		if (!in_array($userdata->user_nicename, $coauthors)) {
			array_unshift($coauthors, $userdata->user_nicename);
		}

		$coauthors_plus->add_coauthors( $post_id, $coauthors );
	}
}


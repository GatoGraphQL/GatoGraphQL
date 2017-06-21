<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Disclaimer functionality
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DISCLAIMER_NONCE', 'gd_disclaimer');

function gd_get_disclaimer_url($post_id = null) {

	if (!$post_id) { 
		$vars = GD_TemplateManager_Utils::get_vars();
		$post = $vars['global-state']['post']/*global $post*/;
		$post_id = $post->ID;
	}
	return GD_MetaManager::get_post_meta( $post_id, GD_METAKEY_POST_DISCLAIMERURL, true);
}

add_filter( 'the_content', 'gd_show_disclaimer', 10000);
function gd_show_disclaimer($content) {

	$vars = GD_TemplateManager_Utils::get_vars();
	if ($vars['global-state']['is-single']/*is_single()*/) {

		if ($disclaimer_url = gd_get_disclaimer_url()) {

			$msg = sprintf(
				__('%sThis information was gathered and published by %s from <a href="%s">this source</a>.', 'poptheme-wassup'),
				'<i class="fa fa-warning fa-fw"></i>',
				get_bloginfo('name'),
				maybe_add_http($disclaimer_url)
			);

			// $content .= sprintf('<div class="alert alert-warning disclaimer">%s</div>', $msg);
			$content = sprintf(
				'<p class="bg-warning text-warning disclaimer"><em>%s</em></p>%s', 
				$msg,
				$content
			);
		}
	}
	
	return $content;
}


// When the user updates a post, delete the disclaimer
add_action('gd_createupdate_post:update', 'gd_disclaimer_delete_disclaimer', 10, 1);
function gd_disclaimer_delete_disclaimer($post_id) {

	delete_post_meta($post_id, GD_MetaManager::get_meta_key(GD_METAKEY_POST_DISCLAIMERURL, GD_META_TYPE_POST));	
}


/**---------------------------------------------------------------------------------------------------------------
 * Allows to add the Disclaimer to the post in the Backend
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('admin_init', 'gd_disclaimer_add_meta_box');
function gd_disclaimer_add_meta_box() {

	// $screens = apply_filters('gd_disclaimer_posttypes', array('post'));
	$screens = apply_filters('gd_content_posttypes', array('post'));
    foreach($screens as $screen) {

		// Add in the Post
		add_meta_box('gd-disclaimer',
					__( 'Disclaimer', 'poptheme-wassup' ),
					'gd_disclaimer_meta_box_content',
					$screen, 'normal', 'low' 
					);
	}
}

function gd_disclaimer_meta_box_content() {
	
	wp_nonce_field( GD_DISCLAIMER_NONCE, "gd_disclaimer_nonce" );

	$submitted = ( 'POST' == $_SERVER['REQUEST_METHOD'] );
	if ($submitted) {	
		$disclaimer = $_POST['disclaimer_url'];
	}
	else {	
		$disclaimer = gd_get_disclaimer_url();
	}

	printf(__('Original URL (for Disclaimer):<br/>%s', 'poptheme-wassup'), 
		'<input type=text name="disclaimer_url" id="disclaimer_url" value="'.$disclaimer.'" style="width: 100%;">'
	);
}


add_action('save_post', 'gd_disclaimer_meta_box_save'); 
function gd_disclaimer_meta_box_save($post_id) {

	if ( @!wp_verify_nonce( $_POST["gd_disclaimer_nonce"], GD_DISCLAIMER_NONCE )) {
		return $post_id;
	}

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;

	$gd_disclaimer_url = trim($_POST['disclaimer_url']);
	
	//update_post_meta( $post_id, GD_DISCLAIMER_METAKEY, $gd_disclaimer);
	GD_MetaManager::update_post_meta( $post_id, GD_METAKEY_POST_DISCLAIMERURL, $gd_disclaimer_url, true);
}


/**---------------------------------------------------------------------------------------------------------------
 * Profile Owned By us?
 * ---------------------------------------------------------------------------------------------------------------*/
function is_profile_owned_by_us($profile_id) {

	return GD_MetaManager::get_user_meta($profile_id, GD_METAKEY_PROFILE_OWNEDBYUS, true);
}

add_filter('the_author_page_description', 'gd_profile_owned_by_us_message', 10, 2);
function gd_profile_owned_by_us_message($description, $profile_id) {

	if (is_profile_owned_by_us($profile_id)) {
	
		$description .= 
			'<p class="bg-warning text-warning"><em>' .
				sprintf(__("This Profile was created by %s with information available on the Organization's website.", 'poptheme-wassup'),
						get_bloginfo('name')
				) .
			'</em></p>';
	}

	return $description;							   
}

// When the user updates his profile, delete the 'profile_owned_by_gd'
// Same when updating the password
add_action('gd_createupdate_user:additionals_update', 'gd_disclaimer_delete_profile_owned_by_us', 10, 2);
add_action('gd_changepassword_user', 'gd_disclaimer_delete_profile_owned_by_us', 10, 2);
function gd_disclaimer_delete_profile_owned_by_us($user_id, $form_data) {

	delete_user_meta($user_id, GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_OWNEDBYUS, GD_META_TYPE_USER));	
}

<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Adding extra fields to the Registration
 *
 * ---------------------------------------------------------------------------------------------------------------*/


/* Contact Methods for the Edit User Page for the WP backend*/
add_filter( 'user_contactmethods', 'gd_user_contactmethods' );
function gd_user_contactmethods() {

	$contact = array(
				'linkedin' => __( 'Linkedin' ),
				'facebook' => __( 'Facebook'), 
				'twitter' => __( 'Twitter'),
				'youtube' => __( 'Youtube')
				);
	
	return $contact;
}

add_action('show_user_profile', 'custom_extra_user_profile_fields', 2);
add_action('edit_user_profile', 'custom_extra_user_profile_fields', 2);

add_action( 'personal_options_update', 'custom_save_extra_user_profile_fields', 20);
add_action( 'edit_user_profile_update', 'custom_save_extra_user_profile_fields', 20);

function custom_extra_user_profile_fields( $user ) { 

	if (!is_admin()) return;

	?>
	<h3><?php printf(__('Profile owned by %s?', 'poptheme-wassup'), get_bloginfo('name')) ?></h3>		
	<table class="form-table">
	<tr>
	<th><label for="display_email"><?php printf(__('Profile owned by %s?', 'poptheme-wassup'), get_bloginfo('name')) ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('profile_owned_by_us', gd_build_select_options(array('Yes', 'No')), isset($_POST['profile_owned_by_us']) ? $_POST['profile_owned_by_us'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_OWNEDBYUS, true)  ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	</table>
	
	<h3><?php _e('Organization info', 'poptheme-wassup') ?></h3>		
	<table class="form-table">
	<tbody>				
		<tr>
			<th><label for="contact_person"><?php _e('Contact person', 'poptheme-wassup'); ?></label></th>
			<td><input class="text-input" name="contact_person" type="text" id="contact_person" value="<?php if (isset($_POST['contact_person'])) echo $_POST["contact_person"]; else echo GD_MetaManager::get_user_meta($user->ID, GD_URE_METAKEY_PROFILE_CONTACTPERSON, true); ?>" /></td>
		</tr>
		<tr>
			<th><label for="contact_number"><?php _e('Contact number', 'poptheme-wassup'); ?></label></th>
			<td><input class="text-input" name="contact_number" type="text" id="contact_number" value="<?php if (isset($_POST['contact_number'])) echo $_POST["contact_number"]; else echo GD_MetaManager::get_user_meta($user->ID, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, true); ?>" /></td>
		</tr>																			
	</tbody>
	</table>
<?php
} 
 
function custom_save_extra_user_profile_fields( $user_id ) {

	if (!is_admin()) return;

	// Is community?
	$user = get_user_by('id', $user_id);
	
	$profile_owned_by_us = isset($_POST['profile_owned_by_us']) && $_POST['profile_owned_by_us'] == "yes";
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_OWNEDBYUS, $profile_owned_by_us, true);
	GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_CONTACTPERSON, esc_attr($_POST['contact_person']), true );
	GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, esc_attr($_POST['contact_number']), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_FACEBOOK, esc_attr($_POST['facebook']), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_TWITTER, esc_attr($_POST['twitter']), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_LINKEDIN, esc_attr($_POST['linkedin']), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_YOUTUBE, esc_attr($_POST['youtube']), true );
	
	// The $display_name also works for nickname: it is needed for searching (can search nickname, but not display_name)
	$display_name = calculate_best_display_name($user_id);

	// Update display name. This function must be executed at the end (after updating first and last names)
	$updates = array (
		'ID' => $user_id, 
		'display_name' => $display_name,
		// 'nickname'	=> $display_name
	);

	wp_update_user($updates);
}


/**
 * Returns the best suitable 'Nice name' and 'Display name'
 * Returns: Array[0] = display_name, Array[1] = nice_name
 */
function calculate_best_display_name($user_id) {

	// $registration_type = get_user_registration_type($user_id);
	
	$first_name = get_the_author_meta( 'user_firstname', $user_id );
	$last_name = get_the_author_meta( 'user_lastname', $user_id );
	$user_login = get_the_author_meta( 'user_login', $user_id );
	
	// Organizations
	if (gd_ure_is_organization($user_id)) {
		return $first_name;
	}

	// Individuals
	if (!$first_name && !$last_name) 
		return $user_login;
		
	if (!$first_name) 
		return $last_name;
		
	if (!$last_name) 
		return $first_name;
		
	return sprintf("%s %s", $first_name, $last_name);
}


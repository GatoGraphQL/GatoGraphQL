<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Gravity Forms plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// These are Notification names as defined in the Gravity Forms settings for the form
define ('GD_GF_NOTIFICATION_PROFILES', 'Notification to Profiles');
define ('GD_GF_NOTIFICATION_POSTAUTHORS', 'Notification to Post Owners');
define ('GD_GF_NOTIFICATION_DESTINATIONEMAIL', 'Notification to Destination Email');


add_filter("gform_notification", "gd_gf_change_autoresponder_email_profiles", 10, 3);
function gd_gf_change_autoresponder_email_profiles( $notification, $form, $entry ) {

	if ($notification['name'] == GD_GF_NOTIFICATION_PROFILES) {

		global $gd_template_processor_manager;
		$profiles_processor = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_USERID);
		if ($profiles_ids = $profiles_processor->get_value(GD_TEMPLATE_FORMCOMPONENT_USERID)) {
		
			$emails = array();				
			$profiles = explode(',', $profiles_ids);
			foreach ($profiles as $profile_id) {
			
				$emails[] = get_the_author_meta( 'user_email', $profile_id );
			}
		
			$notification['to'] = implode(', ', $emails);
		}
	}

	return $notification;
}

add_filter("gform_notification", "gd_gf_change_autoresponder_email_postowners", 10, 3);
function gd_gf_change_autoresponder_email_postowners( $notification, $form, $entry ) {

	if( $notification['name'] == GD_GF_NOTIFICATION_POSTAUTHORS) {

		global $gd_template_processor_manager;
		$profiles_processor = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID);
		if ($post_ids = $profiles_processor->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID)) {
		
			$emails = array();
			foreach (explode(',', $post_ids) as $post_id) {
				$profiles = gd_get_postauthors($post_id);
				foreach ($profiles as $profile_id) {
				
					$emails[] = get_the_author_meta( 'user_email', $profile_id );
				}
			}
		
			$notification['to'] = implode(', ', $emails);
		}
	}

	return $notification;
}


// Add the general layout of the MESYM newsletters to the email
add_filter("gform_notification", "gd_gf_email_layout", 10, 3);
function gd_gf_email_layout( $notification, $form, $entry ) {

	$title = sprintf(
		__('Notification from %s', 'poptheme-wassup'),
		get_bloginfo('name')
	);
	$names = array();
	$user_ids = array();
	$emails = array();

	// Check if the recipient of the email is known. If so, extract their names
	global $gd_template_processor_manager;
	if ($notification['name'] == GD_GF_NOTIFICATION_PROFILES) {

		if ($ids = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_USERID)->get_value(GD_TEMPLATE_FORMCOMPONENT_USERID)) {
			$user_ids = explode(',', $ids);
		}
	}
	elseif( $notification['name'] == GD_GF_NOTIFICATION_POSTAUTHORS) {

		if ($post_ids = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID)) {
			foreach (explode(',', $post_ids) as $post_id) {
				$user_ids = array_merge(
					$user_ids,
					gd_get_postauthors($post_id)
				);
			}
		}
	}
	if ($user_ids) {
		
		foreach ($user_ids as $user_id) {
		
			$names[] = get_the_author_meta('display_name', $user_id);
			$emails[] = get_the_author_meta('user_email', $user_id);
		}
	}

	$notification['message'] = PoP_EmailTemplates_Factory::get_instance()->add_emailframe($title, $notification['message'], $emails, $names, GD_EMAIL_TEMPLATE_EMAILBODY);

	return $notification;
}
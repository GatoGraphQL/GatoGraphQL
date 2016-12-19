<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Create / Update Post
 * ---------------------------------------------------------------------------------------------------------------*/

// Send an email to the new Communities: when there's a new user, when the user updated the communities
add_action('gd_createupdate_profile:additionals_create', 'gd_ure_sendemail_createuser', 100, 2);
function gd_ure_sendemail_createuser($user_id, $form_data) {

	gd_ure_sendemail_community_newmember($user_id, $form_data['communities']);
}
add_action('gd_update_mycommunities:update', 'gd_ure_sendemail_updatemycommunities', 100, 3);
function gd_ure_sendemail_updatemycommunities($user_id, $form_data, $operationlog) {

	gd_ure_sendemail_community_newmember($user_id, $operationlog['new-communities']);
}

function gd_ure_sendemail_community_newmember($user_id, $communities) {

	if (!$communities) return;

	$author_name = get_the_author_meta( 'display_name', $user_id);
	$user_html = PoP_EmailTemplates_Factory::get_instance()->get_userhtml($user_id);
	
	foreach ($communities as $community) {

		// New Community => Send an email informing of the new member
		$community_url = get_author_posts_url($community);
		$community_name = get_the_author_meta( 'display_name', $community);
		$subject = sprintf( __('%s has a new member!', 'ure-popprocessors'), $community_name);
		
		$community_html = sprintf(
			'<a href="%s">%s</a>',
			$community_url,
			$community_name
		);
	
		$content = sprintf(
			__( '<p>Congratulations! <a href="%s">Your organization has a new member</a>:</p>', 'ure-popprocessors'),
			GD_TemplateManager_Utils::add_tab($community_url, POP_URE_POPPROCESSORS_PAGE_MEMBERS)
		);
		$content .= $user_html;
		$content .= '<br/>';
		$content .= sprintf(
			__( '<p>Please <a href="%s">click here to configure the settings of this user as a member of your Organization</a>:</p>', 'ure-popprocessors'),
			gd_ure_edit_membership_url($user_id)
		);
		$content .= '<ul>';
		$content .= sprintf(
			__( '<li>Accept or Reject <b>%s</b> as a member of your Organization. Currently: accepted.</li>', 'ure-popprocessors'),
			$author_name,
			$community_html
		);
		$content .= sprintf(
			__( '<li>Determine if the content posted by <b>%s</b> will also appear under %s. Currently: yes.</li>', 'ure-popprocessors'),
			$author_name,
			$community_html
		);
		$content .= sprintf(
			__( '<li>Choose what type of member <b>%s</b> is (Staff, Volunteer, Student, etc). Currently: \'Member\'.</li>', 'ure-popprocessors'),
			$author_name,
			$community_html
		);
		$content .= '</ul>';
		$content .= sprintf(
			__( '<p>To view all your current members, and edit their membership settings, please click on <a href="%s">%s</a>.</p>', 'ure-popprocessors'),
			get_permalink(POP_URE_POPPROCESSORS_PAGE_MYMEMBERS),
			get_the_title(POP_URE_POPPROCESSORS_PAGE_MYMEMBERS)
		);
	
		$email = get_the_author_meta('user_email', $community);	
		sendemail_to_users($email, $community_name, $subject, $content);	
	}
}

<?php

add_filter('GD_InviteUsers:emailsubject', 'ofp_inviteusers_emailsubject', 10, 2);
function ofp_inviteusers_emailsubject($subject, $sender_name) {

	// Maybe the user is logged in, maybe not
	if ($sender_name = $form_data['sender-name']) {

		$subject = sprintf( 
			__( '%s is inviting you to support %s!', 'pop-coreprocessors'), 
			$sender_name,
			get_bloginfo('name')
		);
	}
	else {
	
		$subject = sprintf( 
			__( 'You have been invited to support %s!', 'pop-coreprocessors' ), 
			get_bloginfo('name')
		);
	}
	
	return $subject;
}

add_filter('GD_InviteUsers:emailcontent', 'ofp_inviteusers_emailcontent', 10, 3);
function ofp_inviteusers_emailcontent($content, $sender_html, $website_html) {

	if ($sender_html) {

		$content = sprintf(
			__( '<p>%s is inviting you to support %s!</p>', 'pop-coreprocessors'),
			$sender_html,
			$website_html
		);
	}
	else {

		$content = sprintf(
			__( '<p>You have been invited to support %s!</p>', 'pop-coreprocessors'),
			$website_html
		);
	}
	
	return $content;
}



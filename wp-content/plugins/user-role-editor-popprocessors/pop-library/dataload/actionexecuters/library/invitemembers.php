<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_InviteMembers extends GD_EmailInvite {

	protected function get_email_content($form_data) {

		// The user must be always logged in, so we will have the user_id
		$user_id = $form_data['user_id'];

		$author_url = get_author_posts_url($user_id);
		$author_name = get_the_author_meta( 'display_name', $user_id);
		
		$user_html = gd_sendemail_get_userhtml($user_id);//PoP_EmailUtils::get_user_html($user_id);
		$website_html = gd_sendemail_get_websitehtml();//PoP_EmailUtils::get_website_html();

		$content = sprintf(
			__( '<p><a href="%s">%s</a> is inviting you to <a href="%s">become their member</a>:</p>', 'ure-popprocessors'),
			$author_url, 
			$author_name,
			GD_TemplateManager_Utils::add_tab($author_url, POP_URE_POPPROCESSORS_PAGE_MEMBERS)
		);
		// Optional: Additional Message
		if ($additional_msg = $form_data['additional-msg']) {
			$content .= sprintf(
				'<div style="margin-left: 20px;">%s</div>',
				make_clickable(wpautop($additional_msg))
			);		
		}
		$content .= $user_html;
		$content .= '<br/>';
		$content .= sprintf(
			'<h3>%s</h3>',
			sprintf(
				__('How to become %s\'s member?', 'ure-popprocessors'),
				$author_name
			)
		);
		$content .= '<ul><li>';
		$content .= sprintf(
			__( 'If you do not have an account in %s yet:<br/>Register <a href="%s">as an Individual</a> / <a href="%s">as an Organization</a>, and while doing so, select <strong>%s</strong> in section "My Organizations".', 'ure-popprocessors'),
			$website_html,
			get_permalink(POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL),
			get_permalink(POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION),
			$author_name
		);
		$content .= '</li><li>';
		$content .= sprintf(
			__( '<p>If you have already have an account in %s:<br/>Go to <a href="%s">%s</a>, select <strong>%s</strong> and submit.</p>', 'ure-popprocessors'),
			$website_html,
			get_permalink(POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES),
			get_the_title(POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES),
			$author_name
		);
		$content .= '</li></ul>';

		return $content;
	}

	protected function get_email_subject($form_data) {

		// The user must be always logged in, so we will have the user_id
		$user_id = $form_data['user_id'];
		return sprintf( 
			__( '%s is inviting you to become their member!' ), 
			get_the_author_meta('display_name', $user_id)
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_invitemembers;
$gd_invitemembers = new GD_InviteMembers();
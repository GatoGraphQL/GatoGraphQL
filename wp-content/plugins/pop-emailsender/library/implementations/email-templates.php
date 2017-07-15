<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EMAIL_FRAME_DEFAULT', 'default');
define ('GD_EMAIL_TEMPLATE_BUTTON', 'button.html');

class PoP_EmailSender_Templates_Simple extends PoP_EmailSender_Templates {

	function get_name() {

		return GD_EMAIL_FRAME_DEFAULT;
	}

	function get_emailframe_header(/*$frame, */$title, $emails, $names, $template) {

		// Message
		if ($names) {
			return sprintf(
				__('<p>Hi %s,</p>', 'pop-emailsender'), 
				implode(', ', $names)
			);
		}
		return __('<p>Howdy!</p>', 'pop-emailsender');
	}

	function get_emailframe_footer(/*$frame, */$title, $emails, $names, $template) {
		
		$url = trailingslashit(home_url());
		return sprintf(
			'<p><a href="%s">%s</a><br/>%s</p>', 
			$url,
			get_bloginfo('name'),
			get_bloginfo('description')
		);
	}

	function get_userhtml($user_id) {

		$author_url = get_author_posts_url($user_id);
		$author_name = get_the_author_meta( 'display_name', $user_id);
		$avatar = gd_get_avatar($user_id, GD_AVATAR_SIZE_60);
		$avatar_html = sprintf(
			'<a href="%1$s"><img src="%2$s" width="%3$s" height="%3$s"></a>',
			$author_url,
			$avatar['src'],
			$avatar['size']
		);
		$name_html = sprintf(
			'<h3 style="display: block;"><a href="%s">%s</a></h3>%s',
			$author_url,
			$author_name,
			gd_get_user_shortdescription($user_id)
		);

		$userhtml_styles = apply_filters('sendemail_get_userhtml:userhtml_styles', array('width: 100%'));
		$user_html = sprintf(
			'<table cellpadding=10 cellspacing=0 border=0 style="%s">'.
				'<tr valign="top">'.
					'<td width="%s" valign="top">%s</td><td valign="top">%s</td>'
				.'</tr>'.
			'</table>',
			implode(';', $userhtml_styles),
			$avatar['size'],
			$avatar_html,
			$name_html
		);
		
		return $user_html;
	}

	function get_posthtml($post_id) {

		$post_url = get_permalink($post_id);
		$post_title = get_the_title($post_id);
		$post_excerpt = get_post_excerpt($post_id);
		$thumb = wp_get_attachment_image_src(gd_get_thumb_id($post_id), 'thumb-sm');
		$thumb_html = sprintf(
			'<a href="%1$s"><img src="%2$s" width="%3$s" height="%4$s"></a>',
			$post_url,
			$thumb[0],
			$thumb[1],
			$thumb[2]
		);
		$title_html = sprintf(
			'<h3 style="display: block;"><a href="%s">%s</a></h3>',
			$post_url,
			$post_title
		);

		$posthtml_styles = apply_filters('sendemail_get_userhtml:posthtml_styles', array('width: 100%'));
		$post_html = sprintf(
			'<table cellpadding=10 cellspacing=0 border=0 style="%s">'.
				'<tr valign="top">'.
					'<td width="%s" valign="top">%s</td><td valign="top"><div>%s</div><div>%s</div></td>'
				.'</tr>'.
			'</table>',
			implode(';', $posthtml_styles),
			$thumb[1],
			$thumb_html,
			$title_html,
			$post_excerpt
		);
		
		return $post_html;
	}

	function get_commenthtml($comment) {

		$avatar = gd_get_avatar($comment->user_id, GD_AVATAR_SIZE_40);
		$avatar_html = sprintf(
			'<a href="%1$s"><img src="%2$s" width="%3$s" height="%3$s"></a>',
			$comment->comment_author_url,
			$avatar['src'],
			$avatar['size']
		);
		
		$comment_styles = apply_filters('sendemail_to_users_from_comment:comment_styles', array('width: 100%'));
		$comment_html = sprintf(
			'<table cellpadding=10 cellspacing=0 border=0 style="%s">'.
				'<tr valign="top">'.
					'<td width="%s" valign="top">%s</td><td valign="top"><a href="%s">%s</a>&nbsp;<small>%s</small><br/>%s</td>'
				.'</tr>'.
			'</table>',
			implode(';', $comment_styles),
			$avatar['size'],
			$avatar_html,
			$comment->comment_author_url,
			$comment->comment_author,
			mysql2date(get_option('date_format'), $comment->comment_date_gmt),
			gd_comments_apply_filters($comment->comment_content)
		);
		
		return $comment_html;
	}

	function get_commentcontenthtml($comment) {

		$post_id = $comment->comment_post_ID;
		$title = get_the_title($post_id);
		$url = get_permalink($post_id);

		$is_response = false;
		if ($comment->comment_parent) {
			$parent = get_comment($comment->comment_parent);
			$is_response = true;
		}

		$intro = $is_response ?
			__( '<p>There is a response to a comment from <a href="%s">%s</a>:</p>', 'pop-emailsender') :
			__( '<p>A new comment has been added to <a href="%s">%s</a>:</p>', 'pop-emailsender');

		$content = sprintf( 
			$intro,
			$url,
			$title
		);
		
		$content .= $this->get_commenthtml($comment);
		$content .= '<br/>';
		if ($parent) {
			
			$content .= sprintf(
				'<em>%s</em>',
				__('In response to:', 'pop-emailsender')
			);
			$content .= $this->get_commenthtml($parent);
			$content .= '<br/>';
		}

		$btn_title = __( 'Click here to reply the comment', 'pop-emailsender');
		$content .= $this->get_buttonhtml($btn_title, $url);
		
		return $content;
	}

	function get_taghtml($tag_id) {

		$tag = get_tag($tag_id);
		$tag_url = get_tag_link($tag_id);
		$tagname_html = sprintf(
			'<h3 style="display: block;"><a href="%s">%s</a></h3>',
			$tag_url,
			PoP_TagUtils::get_tag_namedescription($tag, true)
		);
		$userhtml_styles = apply_filters('sendemail_get_userhtml:userhtml_styles', array('width: 100%'));
		$tag_html = sprintf(
			'<table cellpadding=10 cellspacing=0 border=0 style="%s">'.
				'<tr valign="top">'.
					'<td valign="top">%s</td>'
				.'</tr>'.
			'</table>',
			implode(';', $userhtml_styles),
			$tagname_html
		);
		
		return $tag_html;
	}

	function get_websitehtml() {

		return sprintf(
			'<a href="%s">%s</a>',
			get_site_url(),
			get_bloginfo('name')
		);
	}

	function get_buttonhtml($title, $url) {

		$template = '';
		foreach ($this->get_template_folders() as $template_folder) {

			if (file_exists($template_folder . GD_EMAIL_TEMPLATE_BUTTON)) {
				$template = $template_folder . GD_EMAIL_TEMPLATE_BUTTON;
				break;
			}
		}

		if ($template) {

			ob_start();
			include ($template);
			$button = ob_get_clean();
			return str_replace(
				array('{{TITLE}}', '{{URL}}'), 
				array($title, $url), 
				$button
			);
		}

		return '';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EmailSender_Templates_Simple();

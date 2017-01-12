<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOCATIONPOSTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-locationposts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORIES', PoP_ServerUtils::get_template_definition('layout-messagefeedback-stories'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-announcements'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSIONS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-discussions'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED', PoP_ServerUtils::get_template_definition('layout-messagefeedback-featured'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_BLOG', PoP_ServerUtils::get_template_definition('layout-messagefeedback-blog'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_THOUGHTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-thoughts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mylocationposts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYSTORIES', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mystories'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-myannouncements'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mydiscussions'));

class GD_Custom_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOCATIONPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSIONS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_BLOG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_THOUGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLOCATIONPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYSTORIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYANNOUNCEMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYDISCUSSIONS,
		);
	}

	function checkpoint($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLOCATIONPOSTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYSTORIES:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYANNOUNCEMENTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYDISCUSSIONS:

				return true;
		}

		return false;
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOCATIONPOSTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLOCATIONPOSTS:
			
				// $name = __('locationpost', 'poptheme-wassup-sectionprocessors');
				// $names = __('locationposts', 'poptheme-wassup-sectionprocessors');
				$cat = POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS;
				$name = gd_get_categoryname($cat, 'lc');
				$names = gd_get_categoryname($cat, 'plural-lc');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORIES:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYSTORIES:
			
				$name = __('story', 'poptheme-wassup-sectionprocessors');
				$names = __('stories', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYANNOUNCEMENTS:
			
				$name = __('announcement', 'poptheme-wassup-sectionprocessors');
				$names = __('announcements', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSIONS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYDISCUSSIONS:
			
				$name = __('article', 'poptheme-wassup-sectionprocessors');
				$names = __('articles', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED:
			
				$name = __('featured article', 'poptheme-wassup-sectionprocessors');
				$names = __('featured articles', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_BLOG:
			
				$name = __('blog entry', 'poptheme-wassup-sectionprocessors');
				$names = __('blog entries', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_THOUGHTS:

				$name = __('thought', 'poptheme-wassup-sectionprocessors');
				$names = __('thoughts', 'poptheme-wassup-sectionprocessors');
				break;
		}

		$ret['noresults'] = sprintf(
			__('No %s found.', 'poptheme-wassup-sectionprocessors'),
			$names
		);
		$ret['nomore'] = sprintf(
			__('No more %s found.', 'poptheme-wassup-sectionprocessors'),
			$names
		);

		if ($this->checkpoint($template_id, $atts)) {

			$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup-sectionprocessors');

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to access your %s.', 'poptheme-wassup-sectionprocessors'),
				gd_get_login_html(),
				$names
			);

			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to access this functionality.', 'poptheme-wassup-sectionprocessors'),
				get_bloginfo('name')
			);

			// User is trying to edit a post which he/she doens't own
			$ret['usercannotedit'] = sprintf(
				__('Your account has no permission to edit this %s.', 'poptheme-wassup-sectionprocessors'),
				$name
			);

			// The link doesn't contain the nonce
			$ret['nonceinvalid'] = __('Incorrect URL', 'pop-wpapi');
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CustomListMessageFeedbackLayouts();
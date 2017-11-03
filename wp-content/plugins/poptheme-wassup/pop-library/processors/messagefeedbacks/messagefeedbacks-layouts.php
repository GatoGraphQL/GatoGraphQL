<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLCONTENT', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-allcontent'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINKS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-links'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-highlights'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOSTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-webposts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLPROFILES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-allprofiles'));
// define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RESOURCES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-resources'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-users'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-followers'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SPONSORS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-sponsors'));
// define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATUREDCOMMUNITIES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-featuredcommunities'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCONTENT', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-mycontent'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLINKS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-mylinks'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYHIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-myhighlights'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-mywebposts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_TAGS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-tags'));
// define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYRESOURCES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-myresources'));

class GD_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLCONTENT,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINKS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLPROFILES,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RESOURCES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SPONSORS,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATUREDCOMMUNITIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCONTENT,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLINKS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYHIGHLIGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYWEBPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_TAGS,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYRESOURCES,
		);
	}

	function checkpoint($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCONTENT:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLINKS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYHIGHLIGHTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYWEBPOSTS:
			// case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYRESOURCES:

				return true;
		}

		return false;
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLCONTENT:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCONTENT:
			
				$name = __('content', 'poptheme-wassup');
				$names = __('content', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINKS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLINKS:
			
				$name = __('link', 'poptheme-wassup');
				$names = __('links', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYHIGHLIGHTS:
			
				$name = __('extract', 'poptheme-wassup');
				$names = __('extracts', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOSTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYWEBPOSTS:
			
				$name = __('posts', 'poptheme-wassup');
				$names = __('posts', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLPROFILES:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERS:
			
				$name = __('user', 'poptheme-wassup');
				$names = __('users', 'poptheme-wassup');
				break;

			// case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RESOURCES:
			// case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYRESOURCES:

			// 	$name = __('resource', 'poptheme-wassup');
			// 	$names = __('resources', 'poptheme-wassup');
			// 	break;
			
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWERS:

				$name = __('follower', 'poptheme-wassup');
				$names = __('followers', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SPONSORS:

				$name = __('sponsor', 'poptheme-wassup');
				$names = __('sponsors', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_TAGS:

				$name = __('tag', 'poptheme-wassup');
				$names = __('tags', 'poptheme-wassup');
				break;

			// case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATUREDCOMMUNITIES:
			
			// 	$name = __('featured community', 'poptheme-wassup');
			// 	$names = __('featured communities', 'poptheme-wassup');
			// 	break;
		}

		$ret['noresults'] = sprintf(
			__('No %s found.', 'poptheme-wassup'),
			$names
		);
		$ret['nomore'] = sprintf(
			__('No more %s found.', 'poptheme-wassup'),
			$names
		);

		if ($this->checkpoint($template_id, $atts)) {

			$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup');

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to access your %s.', 'poptheme-wassup'),
				gd_get_login_html(),
				$names
			);

			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to access this functionality.', 'poptheme-wassup'),
				get_bloginfo('name')
			);

			// User is trying to edit a post which he/she doens't own
			$ret['usercannotedit'] = sprintf(
				__('Your account has no permission to edit this %s.', 'poptheme-wassup'),
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
new GD_Template_Processor_CustomListMessageFeedbackLayouts();
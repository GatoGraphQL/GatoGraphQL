<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS00', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts00'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS01', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts01'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS02', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts02'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS03', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts03'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS04', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts04'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS05', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts05'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS06', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts06'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS07', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts07'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS08', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts08'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS09', PoP_ServerUtils::get_template_definition('layout-messagefeedback-categoryposts09'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts00'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS01', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts01'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS02', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts02'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS03', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts03'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS04', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts04'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS05', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts05'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS06', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts06'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS07', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts07'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS08', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts08'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS09', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mycategoryposts09'));

class CPP_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS01,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS02,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS03,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS04,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS05,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS06,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS07,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS08,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS09,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00,
		);
	}

	function checkpoint($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS01:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS02:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS03:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS04:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS05:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS06:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS07:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS08:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS09:

				return true;
		}

		return false;
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS00:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS00:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS01:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS01:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS01;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS02:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS02:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS02;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS03:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS03:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS03;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS04:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS04:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS04;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS05:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS05:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS05;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS06:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS06:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS06;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS07:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS07:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS07;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS08:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS08:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS08;
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CATEGORYPOSTS09:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCATEGORYPOSTS09:
			
				$cat = POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS09;
				break;
		}

		if ($cat) {

			$name = gd_get_categoryname($cat, 'lc');
			$names = gd_get_categoryname($cat, 'plural-lc');

			$ret['noresults'] = sprintf(
				__('No %s found.', 'poptheme-wassup-categoryprocessors'),
				$names
			);
			$ret['nomore'] = sprintf(
				__('No more %s found.', 'poptheme-wassup-categoryprocessors'),
				$names
			);

			if ($this->checkpoint($template_id, $atts)) {

				$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup-categoryprocessors');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please %s to access your %s.', 'poptheme-wassup-categoryprocessors'),
					gd_get_login_html(),
					$names
				);

				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('You need a %s account to access this functionality.', 'poptheme-wassup-categoryprocessors'),
					get_bloginfo('name')
				);

				// User is trying to edit a post which he/she doens't own
				$ret['usercannotedit'] = sprintf(
					__('Your account has no permission to edit this %s.', 'poptheme-wassup-categoryprocessors'),
					$name
				);

				// The link doesn't contain the nonce
				$ret['nonceinvalid'] = __('Incorrect URL', 'pop-wpapi');
			}
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new CPP_Template_Processor_CustomListMessageFeedbackLayouts();
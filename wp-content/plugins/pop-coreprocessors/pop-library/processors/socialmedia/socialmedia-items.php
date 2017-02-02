<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTSOCIALMEDIA_FB', PoP_ServerUtils::get_template_definition('post-socialmedia-fb'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_FB', PoP_ServerUtils::get_template_definition('tag-socialmedia-fb'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_FB', PoP_ServerUtils::get_template_definition('user-socialmedia-fb'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER', PoP_ServerUtils::get_template_definition('post-socialmedia-twitter'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_TWITTER', PoP_ServerUtils::get_template_definition('tag-socialmedia-twitter'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_TWITTER', PoP_ServerUtils::get_template_definition('user-socialmedia-twitter'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS', PoP_ServerUtils::get_template_definition('post-socialmedia-gplus'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_GPLUS', PoP_ServerUtils::get_template_definition('tag-socialmedia-gplus'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_GPLUS', PoP_ServerUtils::get_template_definition('user-socialmedia-gplus'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN', PoP_ServerUtils::get_template_definition('post-socialmedia-linkedin'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN', PoP_ServerUtils::get_template_definition('tag-socialmedia-linkedin'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN', PoP_ServerUtils::get_template_definition('user-socialmedia-linkedin'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW', PoP_ServerUtils::get_template_definition('post-socialmedia-fb-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW', PoP_ServerUtils::get_template_definition('tag-socialmedia-fb-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW', PoP_ServerUtils::get_template_definition('user-socialmedia-fb-preview'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW', PoP_ServerUtils::get_template_definition('post-socialmedia-twitter-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW', PoP_ServerUtils::get_template_definition('tag-socialmedia-twitter-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW', PoP_ServerUtils::get_template_definition('user-socialmedia-twitter-preview'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW', PoP_ServerUtils::get_template_definition('post-socialmedia-gplus-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW', PoP_ServerUtils::get_template_definition('tag-socialmedia-gplus-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW', PoP_ServerUtils::get_template_definition('user-socialmedia-gplus-preview'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW', PoP_ServerUtils::get_template_definition('post-socialmedia-linkedin-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW', PoP_ServerUtils::get_template_definition('tag-socialmedia-linkedin-preview'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW', PoP_ServerUtils::get_template_definition('user-socialmedia-linkedin-preview'));

class GD_Template_Processor_SocialMediaItems extends GD_Template_Processor_SocialMediaItemsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTSOCIALMEDIA_FB,
			GD_TEMPLATE_USERSOCIALMEDIA_FB,
			GD_TEMPLATE_TAGSOCIALMEDIA_FB,
			GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW,
			GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW,
			GD_TEMPLATE_TAGSOCIALMEDIA_FB_PREVIEW,
			GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER,
			GD_TEMPLATE_USERSOCIALMEDIA_TWITTER,
			GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER,
			GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW,
			GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW,
			GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER_PREVIEW,
			GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS,
			GD_TEMPLATE_USERSOCIALMEDIA_GPLUS,
			GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS,
			GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW,
			GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW,
			GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS_PREVIEW,
			GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN,
			GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN,
			GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN,
			GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW,
			GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW,
			GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW,
		);
	}

	function get_provider($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_FB:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB:
			case GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB_PREVIEW:

				return GD_SOCIALMEDIA_PROVIDER_FACEBOOK;

			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW:

				return GD_SOCIALMEDIA_PROVIDER_TWITTER;

			// case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS:
				// case GD_TEMPTAG_USERSOCIALMEDIA_GPLUS:
			// case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS:
			// case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW:
				// case GD_TEMPTAG_USERSOCIALMEDIA_GPLUS_PREVIEW:
			// case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW:
				
			// 	return GD_SOCIALMEDIA_PROVIDER_GPLUS;
			
			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:

				return GD_SOCIALMEDIA_PROVIDER_LINKEDIN;
		}

		return parent::get_provider($template_id);
	}

	function get_title_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_FB:
			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:

				return 'title';

			case GD_TEMPLATE_USERSOCIALMEDIA_FB:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:

				return 'display-name';

			case GD_TEMPLATE_TAGSOCIALMEDIA_FB:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:

				return 'name';
		}

		return parent::get_title_field($template_id);
	}

	function get_name($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_FB:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB:
			case GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB_PREVIEW:

				return __('Facebook', 'pop-coreprocessors');

			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER_PREVIEW:

				return __('Twitter', 'pop-coreprocessors');

			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS_PREVIEW:

				return __('Google+', 'pop-coreprocessors');

			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:

				return __('LinkedIn', 'pop-coreprocessors');
		}

		return parent::get_name($template_id);
	}
	function get_shortname($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_FB:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB:
			case GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB_PREVIEW:
			
				return 'facebook';

			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER_PREVIEW:

				return 'twitter';

			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS_PREVIEW:

				return 'gplus';

			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:

				return 'linkedin';
		}

		return parent::get_shortname($template_id);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_FB:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB:
			
				return 'fa-facebook fa-lg';

			case GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB_PREVIEW:
			
				return 'fa-facebook';

			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER:

				return 'fa-twitter fa-lg';

			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER_PREVIEW:

				return 'fa-twitter';

			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS:

				return 'fa-google-plus fa-lg';

			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS_PREVIEW:

				return 'fa-google-plus';


			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN:

				return 'fa-linkedin fa-lg';

			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:

				return 'fa-linkedin';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	// function get_shareurl($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_FB:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_FB:
	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW:
			
	// 			// Comment Leo 01/04/2015: Since Twitter needs 2 replacements and %1$s doesn't work for the global replacement,
	// 			// here I change it into another placeholder
	// 			// return 'http://www.facebook.com/share.php?u=%1$s&title=%2$s';
	// 			return 'http://www.facebook.com/share.php?u=%url%&title=%title%';

	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER:
	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW:

	// 			return 'https://twitter.com/intent/tweet?original_referer=%url%&url=%url%&text=%title%';

	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS:
	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS_PREVIEW:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS_PREVIEW:

	// 			return 'https://plus.google.com/share?url=%url%';

	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN:
	// 		case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
	// 		case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:

	// 			return 'http://www.linkedin.com/shareArticle?mini=true&url=%url%';
	// 	}

	// 	return parent::get_shareurl($template_id);
	// }

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_FB:
			case GD_TEMPLATE_USERSOCIALMEDIA_FB:
			case GD_TEMPLATE_TAGSOCIALMEDIA_FB:
			case GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_USERSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER:
			case GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_USERSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_TAGSOCIALMEDIA_GPLUS:
			case GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN:
			case GD_TEMPLATE_TAGSOCIALMEDIA_LINKEDIN:

				$this->append_att($template_id, $atts, 'class', 'socialmedia-changebg icon-only');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SocialMediaItems();
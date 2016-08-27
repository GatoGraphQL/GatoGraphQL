<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER', PoP_ServerUtils::get_template_definition('post-socialmedia-simpleview-volunteer'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER', PoP_ServerUtils::get_template_definition('post-socialmedia-volunteer'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA', PoP_ServerUtils::get_template_definition('post-socialmedia'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER', PoP_ServerUtils::get_template_definition('post-socialmedia-counter'));
define ('GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA', PoP_ServerUtils::get_template_definition('subjugatedpost-socialmedia'));
define ('GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER', PoP_ServerUtils::get_template_definition('subjugatedpost-socialmedia-counter'));
define ('GD_TEMPLATE_USERSOCIALMEDIA', PoP_ServerUtils::get_template_definition('user-socialmedia'));
define ('GD_TEMPLATE_USERSOCIALMEDIA_COUNTER', PoP_ServerUtils::get_template_definition('user-socialmedia-counter'));

class GD_Template_Processor_SocialMedia extends GD_Template_Processor_SocialMediaBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER,
			GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER,
			GD_TEMPLATE_POSTSOCIALMEDIA,
			GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER,
			GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA,
			GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER,
			GD_TEMPLATE_USERSOCIALMEDIA,
			GD_TEMPLATE_USERSOCIALMEDIA_COUNTER,
		);
	}

	function use_counter($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER:
			case GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER:
			case GD_TEMPLATE_USERSOCIALMEDIA_COUNTER:

				return true;
		}
		return parent::use_counter($template_id);
	}

	function get_modules($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:

				return array(
					GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND,
					GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY,
				);

			case GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER:

				return array(
					GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND,
					GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY,
				);
		
			case GD_TEMPLATE_POSTSOCIALMEDIA:
			case GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER:

				return array(
					GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND,
					GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS,
				);
		
			case GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA:
			case GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER:

				// Use the Up/Down vote instead of recomment. Needed for "subjugated" posts
				// Eg: highlights and opinionatedvotes
				return array(
					GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST,
					GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS,
				);

			case GD_TEMPLATE_USERSOCIALMEDIA:
			case GD_TEMPLATE_USERSOCIALMEDIA_COUNTER:

				return array(
					GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER,
					GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS,
				);
		}
		
		return parent::get_modules($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:
			// case GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER:
			// case GD_TEMPLATE_POSTSOCIALMEDIA:
			// case GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER:
			// case GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA:
			// case GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER:
			// case GD_TEMPLATE_USERSOCIALMEDIA:
			// case GD_TEMPLATE_USERSOCIALMEDIA_COUNTER:

				$modules = $this->get_modules($template_id);
				foreach ($modules as $module) {
					$this->append_att($module, $atts, 'class', 'inline');
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SocialMedia();
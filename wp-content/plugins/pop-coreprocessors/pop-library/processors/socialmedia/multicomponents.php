<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA', PoP_ServerUtils::get_template_definition('multicomponent-post-sm'));
define ('GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA', PoP_ServerUtils::get_template_definition('multicomponent-user-sm'));
define ('GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS', PoP_ServerUtils::get_template_definition('multicomponent-postsecinteractions'));
define ('GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS', PoP_ServerUtils::get_template_definition('multicomponent-usersecinteractions'));
define ('GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS', PoP_ServerUtils::get_template_definition('multicomponent-postoptions'));
define ('GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS', PoP_ServerUtils::get_template_definition('multicomponent-useroptions'));

class GD_Template_Processor_SocialMediaMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA,
			GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA,
			GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS,
			GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS,
			GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS,
			GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA:

				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_FB;
				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER;
					// GD_TEMPLATE_POSTSOCIALMEDIA_GPLUS,
					// GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN,
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA:

				$ret[] = GD_TEMPLATE_USERSOCIALMEDIA_FB;
				$ret[] = GD_TEMPLATE_USERSOCIALMEDIA_TWITTER;
					// GD_TEMPLATE_USERSOCIALMEDIA_GPLUS,
					// GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN,
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_EMBED_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA:
			case GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA:

				$this->append_att($template_id, $atts, 'class', 'sm-group');
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS:
			case GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS:

				$this->append_att($template_id, $atts, 'class', 'secinteractions-group');
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS:
			case GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS:

				$this->append_att($template_id, $atts, 'class', 'options-group');
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
new GD_Template_Processor_SocialMediaMultipleComponents();
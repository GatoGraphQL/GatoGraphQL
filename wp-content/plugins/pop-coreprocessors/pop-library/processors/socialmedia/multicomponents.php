<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('multicomponent-post-sm'));
define ('GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('multicomponent-user-sm'));
define ('GD_TEMPLATE_MULTICOMPONENT_TAGSOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('multicomponent-tag-sm'));
define ('GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS', PoP_TemplateIDUtils::get_template_definition('multicomponent-postsecinteractions'));
define ('GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS', PoP_TemplateIDUtils::get_template_definition('multicomponent-usersecinteractions'));
define ('GD_TEMPLATE_MULTICOMPONENT_TAGSECINTERACTIONS', PoP_TemplateIDUtils::get_template_definition('multicomponent-tagsecinteractions'));
define ('GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS', PoP_TemplateIDUtils::get_template_definition('multicomponent-postoptions'));
define ('GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS', PoP_TemplateIDUtils::get_template_definition('multicomponent-useroptions'));
define ('GD_TEMPLATE_MULTICOMPONENT_TAGOPTIONS', PoP_TemplateIDUtils::get_template_definition('multicomponent-tagoptions'));

class GD_Template_Processor_SocialMediaMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA,
			GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA,
			GD_TEMPLATE_MULTICOMPONENT_TAGSOCIALMEDIA,
			GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS,
			GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS,
			GD_TEMPLATE_MULTICOMPONENT_TAGSECINTERACTIONS,
			GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS,
			GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS,
			GD_TEMPLATE_MULTICOMPONENT_TAGOPTIONS,
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

			case GD_TEMPLATE_MULTICOMPONENT_TAGSOCIALMEDIA:

				$ret[] = GD_TEMPLATE_USERSOCIALMEDIA_FB;
				$ret[] = GD_TEMPLATE_USERSOCIALMEDIA_TWITTER;
					// GD_TEMPLATE_USERSOCIALMEDIA_GPLUS,
					// GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN,
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_EMBED_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_API_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_TAGSECINTERACTIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_TAGOPTIONS:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_TAGSOCIALMEDIA;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_TAGSECINTERACTIONS;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA:
			case GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA:
			case GD_TEMPLATE_MULTICOMPONENT_TAGSOCIALMEDIA:

				$this->append_att($template_id, $atts, 'class', 'sm-group');
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS:
			case GD_TEMPLATE_MULTICOMPONENT_USERSECINTERACTIONS:
			case GD_TEMPLATE_MULTICOMPONENT_TAGSECINTERACTIONS:

				$this->append_att($template_id, $atts, 'class', 'secinteractions-group');
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS:
			case GD_TEMPLATE_MULTICOMPONENT_USEROPTIONS:
			case GD_TEMPLATE_MULTICOMPONENT_TAGOPTIONS:

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
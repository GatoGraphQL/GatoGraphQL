<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_ALLCONTENT', PoP_TemplateIDUtils::get_template_definition('messagefeedback-allcontent'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_LINKS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-links'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-highlights'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-webposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_ALLPROFILES', PoP_TemplateIDUtils::get_template_definition('messagefeedback-allprofiles'));
// define ('GD_TEMPLATE_MESSAGEFEEDBACK_RESOURCES', PoP_TemplateIDUtils::get_template_definition('messagefeedback-resources'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_USERS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-users'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_FOLLOWERS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-followers'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_SPONSORS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-sponsors'));
// define ('GD_TEMPLATE_MESSAGEFEEDBACK_FEATUREDCOMMUNITIES', PoP_TemplateIDUtils::get_template_definition('messagefeedback-featuredcommunities'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYCONTENT', PoP_TemplateIDUtils::get_template_definition('messagefeedback-mycontent'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYLINKS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-mylinks'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYHIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-myhighlights'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-mywebposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_TAGS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-tags'));
// define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYRESOURCES', PoP_TemplateIDUtils::get_template_definition('messagefeedback-myresources'));

class GD_Template_Processor_CustomListMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_ALLCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACK_LINKS,
			GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_ALLPROFILES,
			// GD_TEMPLATE_MESSAGEFEEDBACK_RESOURCES,
			GD_TEMPLATE_MESSAGEFEEDBACK_USERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_FOLLOWERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_SPONSORS,
			// GD_TEMPLATE_MESSAGEFEEDBACK_FEATUREDCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYLINKS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYHIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYWEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_TAGS,
			// GD_TEMPLATE_MESSAGEFEEDBACK_MYRESOURCES,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_ALLCONTENT => GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACK_LINKS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINKS,
			GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_ALLPROFILES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLPROFILES,
			// GD_TEMPLATE_MESSAGEFEEDBACK_RESOURCES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_RESOURCES,
			GD_TEMPLATE_MESSAGEFEEDBACK_USERS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_USERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_FOLLOWERS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_SPONSORS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_SPONSORS,
			// GD_TEMPLATE_MESSAGEFEEDBACK_FEATUREDCOMMUNITIES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATUREDCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYCONTENT => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYLINKS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLINKS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYHIGHLIGHTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYWEBPOSTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYWEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_TAGS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_TAGS,
			// GD_TEMPLATE_MESSAGEFEEDBACK_MYRESOURCES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYRESOURCES,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomListMessageFeedbacks();
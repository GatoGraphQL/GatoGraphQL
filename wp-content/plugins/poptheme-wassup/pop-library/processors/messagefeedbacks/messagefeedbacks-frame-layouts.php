<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLCONTENT', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-allcontent'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINKS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-links'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-highlights'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOSTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-webposts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLPROFILES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-allprofiles'));
// define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RESOURCES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-resources'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-users'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-followers'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SPONSORS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-sponsors'));
// define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATUREDCOMMUNITIES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-featuredcommunities'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCONTENT', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-mycontent'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYLINKS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-mylinks'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYHIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-myhighlights'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-mywebposts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_TAGS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-tags'));
// define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYRESOURCES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-myresources'));

class GD_Template_Processor_CustomListMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLCONTENT,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINKS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLPROFILES,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RESOURCES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SPONSORS,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATUREDCOMMUNITIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCONTENT,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYLINKS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYHIGHLIGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYWEBPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_TAGS,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYRESOURCES,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLCONTENT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLCONTENT,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINKS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINKS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOSTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLPROFILES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ALLPROFILES,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RESOURCES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RESOURCES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SPONSORS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SPONSORS,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATUREDCOMMUNITIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATUREDCOMMUNITIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCONTENT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCONTENT,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYLINKS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYLINKS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYHIGHLIGHTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYHIGHLIGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYWEBPOSTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYWEBPOSTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_TAGS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_TAGS,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYRESOURCES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYRESOURCES,
		);

		if ($layout = $layouts[$template_id]) {

			return $layout;
		}

		return parent::get_layout($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomListMessageFeedbackFrameLayouts();
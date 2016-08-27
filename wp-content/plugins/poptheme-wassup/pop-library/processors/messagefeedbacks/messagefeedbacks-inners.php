<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLCONTENT', PoP_ServerUtils::get_template_definition('messagefeedbackinner-allcontent'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINKS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-links'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-highlights'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOSTS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-webposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLPROFILES', PoP_ServerUtils::get_template_definition('messagefeedbackinner-allprofiles'));
// define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_RESOURCES', PoP_ServerUtils::get_template_definition('messagefeedbackinner-resources'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_USERS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-users'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWERS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-followers'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_SPONSORS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-sponsors'));
// define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATUREDCOMMUNITIES', PoP_ServerUtils::get_template_definition('messagefeedbackinner-featuredcommunities'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCONTENT', PoP_ServerUtils::get_template_definition('messagefeedbackinner-mycontent'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLINKS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-mylinks'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-myhighlights'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-mywebposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_TAGS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-tags'));
// define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYRESOURCES', PoP_ServerUtils::get_template_definition('messagefeedbackinner-myresources'));

class GD_Template_Processor_CustomListMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINKS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLPROFILES,
			// GD_TEMPLATE_MESSAGEFEEDBACKINNER_RESOURCES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_USERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_SPONSORS,
			// GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATUREDCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLINKS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYWEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_TAGS,
			// GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYRESOURCES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLCONTENT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINKS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINKS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOSTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ALLPROFILES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ALLPROFILES,
			// GD_TEMPLATE_MESSAGEFEEDBACKINNER_RESOURCES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RESOURCES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_USERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_SPONSORS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SPONSORS,
			// GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATUREDCOMMUNITIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATUREDCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCONTENT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCONTENT,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLINKS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYLINKS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYHIGHLIGHTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYHIGHLIGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYWEBPOSTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYWEBPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_TAGS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_TAGS,
			// GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYRESOURCES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYRESOURCES,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomListMessageFeedbackInners();
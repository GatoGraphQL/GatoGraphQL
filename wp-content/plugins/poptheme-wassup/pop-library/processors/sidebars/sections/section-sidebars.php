<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBAR_SECTION_SEARCHPOSTS', PoP_ServerUtils::get_template_definition('sidebar-section-searchposts'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebar-section-allcontent'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_WEBPOSTLINKS', PoP_ServerUtils::get_template_definition('sidebar-section-webpostlinks'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('sidebar-section-highlights'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_WEBPOSTS', PoP_ServerUtils::get_template_definition('sidebar-section-webposts'));

define ('GD_TEMPLATE_SIDEBAR_SECTION_SEARCHUSERS', PoP_ServerUtils::get_template_definition('sidebar-section-searchusers'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_ALLUSERS', PoP_ServerUtils::get_template_definition('sidebar-section-allusers'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_ALLUSERS_NOFILTER', PoP_ServerUtils::get_template_definition('sidebar-section-allusers-nofilter'));

define ('GD_TEMPLATE_SIDEBAR_SECTION_TRENDINGTAGS', PoP_ServerUtils::get_template_definition('sidebar-section-trendingtags'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_TAGS', PoP_ServerUtils::get_template_definition('sidebar-section-tags'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORTAGS', PoP_ServerUtils::get_template_definition('sidebar-section-authortags'));

define ('GD_TEMPLATE_SIDEBAR_SECTION_MYCONTENT', PoP_ServerUtils::get_template_definition('sidebar-section-mycontent'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYWEBPOSTLINKS', PoP_ServerUtils::get_template_definition('sidebar-section-mywebpostlinks'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('sidebar-section-myhighlights'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('sidebar-section-mywebposts'));

define ('GD_TEMPLATE_SIDEBAR_TAGSECTION_MAINALLCONTENT', PoP_ServerUtils::get_template_definition('sidebar-tagsection-mainallcontent'));
define ('GD_TEMPLATE_SIDEBAR_TAGSECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebar-tagsection-allcontent'));
define ('GD_TEMPLATE_SIDEBAR_TAGSECTION_WEBPOSTS', PoP_ServerUtils::get_template_definition('sidebar-tagsection-webposts'));

define ('GD_TEMPLATE_SIDEBAR_AUTHORSECTION_MAINALLCONTENT', PoP_ServerUtils::get_template_definition('sidebar-authorsection-mainallcontent'));
define ('GD_TEMPLATE_SIDEBAR_AUTHORSECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebar-authorsection-allcontent'));
define ('GD_TEMPLATE_SIDEBAR_AUTHORSECTION_ALLUSERS', PoP_ServerUtils::get_template_definition('sidebar-authorsection-allusers'));

define ('GD_TEMPLATE_SIDEBAR_HOMESECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebar-homesection-allcontent'));

class GD_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBAR_SECTION_SEARCHPOSTS,
			GD_TEMPLATE_SIDEBAR_SECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_SECTION_WEBPOSTLINKS,
			GD_TEMPLATE_SIDEBAR_SECTION_HIGHLIGHTS,
			GD_TEMPLATE_SIDEBAR_SECTION_WEBPOSTS,
			GD_TEMPLATE_SIDEBAR_SECTION_SEARCHUSERS,
			GD_TEMPLATE_SIDEBAR_SECTION_TRENDINGTAGS,
			GD_TEMPLATE_SIDEBAR_SECTION_TAGS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORTAGS,
			GD_TEMPLATE_SIDEBAR_SECTION_ALLUSERS,
			GD_TEMPLATE_SIDEBAR_SECTION_ALLUSERS_NOFILTER,
			GD_TEMPLATE_SIDEBAR_SECTION_MYCONTENT,
			GD_TEMPLATE_SIDEBAR_SECTION_MYWEBPOSTLINKS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYHIGHLIGHTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYWEBPOSTS,
			GD_TEMPLATE_SIDEBAR_TAGSECTION_MAINALLCONTENT,
			GD_TEMPLATE_SIDEBAR_TAGSECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_TAGSECTION_WEBPOSTS,
			GD_TEMPLATE_SIDEBAR_HOMESECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_AUTHORSECTION_MAINALLCONTENT,
			GD_TEMPLATE_SIDEBAR_AUTHORSECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_AUTHORSECTION_ALLUSERS,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_SIDEBAR_SECTION_SEARCHPOSTS => GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHPOSTS,
			GD_TEMPLATE_SIDEBAR_SECTION_ALLCONTENT => GD_TEMPLATE_SIDEBARINNER_SECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_SECTION_WEBPOSTLINKS => GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTLINKS,
			GD_TEMPLATE_SIDEBAR_SECTION_HIGHLIGHTS => GD_TEMPLATE_SIDEBARINNER_SECTION_HIGHLIGHTS,
			GD_TEMPLATE_SIDEBAR_SECTION_WEBPOSTS => GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTS,
			GD_TEMPLATE_SIDEBAR_SECTION_SEARCHUSERS => GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHUSERS,
			GD_TEMPLATE_SIDEBAR_SECTION_ALLUSERS => GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS,
			GD_TEMPLATE_SIDEBAR_SECTION_ALLUSERS_NOFILTER => GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS_NOFILTER,
			GD_TEMPLATE_SIDEBAR_SECTION_TRENDINGTAGS => GD_TEMPLATE_SIDEBARINNER_SECTION_TRENDINGTAGS,
			GD_TEMPLATE_SIDEBAR_SECTION_TAGS => GD_TEMPLATE_SIDEBARINNER_SECTION_TAGS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORTAGS => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORTAGS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYCONTENT => GD_TEMPLATE_SIDEBARINNER_SECTION_MYCONTENT,
			GD_TEMPLATE_SIDEBAR_SECTION_MYWEBPOSTLINKS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTLINKS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYHIGHLIGHTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYHIGHLIGHTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYWEBPOSTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTS,
			GD_TEMPLATE_SIDEBAR_HOMESECTION_ALLCONTENT => GD_TEMPLATE_SIDEBARINNER_HOMESECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_TAGSECTION_MAINALLCONTENT => GD_TEMPLATE_SIDEBARINNER_TAGSECTION_MAINALLCONTENT,
			GD_TEMPLATE_SIDEBAR_TAGSECTION_ALLCONTENT => GD_TEMPLATE_SIDEBARINNER_TAGSECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_TAGSECTION_WEBPOSTS => GD_TEMPLATE_SIDEBARINNER_TAGSECTION_WEBPOSTS,
			GD_TEMPLATE_SIDEBAR_AUTHORSECTION_MAINALLCONTENT => GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_MAINALLCONTENT,
			GD_TEMPLATE_SIDEBAR_AUTHORSECTION_ALLCONTENT => GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBAR_AUTHORSECTION_ALLUSERS => GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLUSERS,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomSectionSidebars();
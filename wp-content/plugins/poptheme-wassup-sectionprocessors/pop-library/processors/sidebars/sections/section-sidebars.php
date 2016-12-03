<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBAR_SECTION_LOCATIONPOSTS', PoP_ServerUtils::get_template_definition('sidebar-section-locationposts'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_STORIES', PoP_ServerUtils::get_template_definition('sidebar-section-stories'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('sidebar-section-announcements'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_DISCUSSIONS', PoP_ServerUtils::get_template_definition('sidebar-section-discussions'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_FEATURED', PoP_ServerUtils::get_template_definition('sidebar-section-featured'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_BLOG', PoP_ServerUtils::get_template_definition('sidebar-section-blog'));

define ('GD_TEMPLATE_SIDEBAR_SECTION_TAGLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('sidebar-section-taglocationposts'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_TAGSTORIES', PoP_ServerUtils::get_template_definition('sidebar-section-tagstories'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_TAGANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('sidebar-section-tagannouncements'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_TAGDISCUSSIONS', PoP_ServerUtils::get_template_definition('sidebar-section-tagdiscussions'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_TAGFEATURED', PoP_ServerUtils::get_template_definition('sidebar-section-tagfeatured'));

define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('sidebar-section-authorlocationposts'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORSTORIES', PoP_ServerUtils::get_template_definition('sidebar-section-authorstories'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('sidebar-section-authorannouncements'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORDISCUSSIONS', PoP_ServerUtils::get_template_definition('sidebar-section-authordiscussions'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORFEATURED', PoP_ServerUtils::get_template_definition('sidebar-section-authorfeatured'));

define ('GD_TEMPLATE_SIDEBAR_SECTION_MYLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('sidebar-section-mylocationposts'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYSTORIES', PoP_ServerUtils::get_template_definition('sidebar-section-mystories'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('sidebar-section-myannouncements'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('sidebar-section-mydiscussions'));

class GD_Custom_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBAR_SECTION_LOCATIONPOSTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_STORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_ANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_DISCUSSIONS, 
			GD_TEMPLATE_SIDEBAR_SECTION_FEATURED, 
			GD_TEMPLATE_SIDEBAR_SECTION_BLOG, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGLOCATIONPOSTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGSTORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_TAGANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGDISCUSSIONS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGFEATURED, 
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORSTORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORDISCUSSIONS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORFEATURED,
			GD_TEMPLATE_SIDEBAR_SECTION_MYLOCATIONPOSTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYSTORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_MYANNOUNCEMENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYDISCUSSIONS,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_SIDEBAR_SECTION_LOCATIONPOSTS => GD_TEMPLATE_SIDEBARINNER_SECTION_LOCATIONPOSTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_STORIES => GD_TEMPLATE_SIDEBARINNER_SECTION_STORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_ANNOUNCEMENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_ANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_DISCUSSIONS => GD_TEMPLATE_SIDEBARINNER_SECTION_DISCUSSIONS, 
			GD_TEMPLATE_SIDEBAR_SECTION_FEATURED => GD_TEMPLATE_SIDEBARINNER_SECTION_FEATURED, 
			GD_TEMPLATE_SIDEBAR_SECTION_BLOG => GD_TEMPLATE_SIDEBARINNER_SECTION_BLOG, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGLOCATIONPOSTS => GD_TEMPLATE_SIDEBARINNER_SECTION_TAGLOCATIONPOSTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGSTORIES => GD_TEMPLATE_SIDEBARINNER_SECTION_TAGSTORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_TAGANNOUNCEMENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_TAGANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGDISCUSSIONS => GD_TEMPLATE_SIDEBARINNER_SECTION_TAGDISCUSSIONS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGFEATURED => GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFEATURED, 
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORLOCATIONPOSTS => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORLOCATIONPOSTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORSTORIES => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORSTORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORANNOUNCEMENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORDISCUSSIONS => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORDISCUSSIONS, 
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORFEATURED => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFEATURED, 
			GD_TEMPLATE_SIDEBAR_SECTION_MYLOCATIONPOSTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYLOCATIONPOSTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYSTORIES => GD_TEMPLATE_SIDEBARINNER_SECTION_MYSTORIES,
			GD_TEMPLATE_SIDEBAR_SECTION_MYANNOUNCEMENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYANNOUNCEMENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYDISCUSSIONS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYDISCUSSIONS,
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
new GD_Custom_Template_Processor_CustomSectionSidebars();
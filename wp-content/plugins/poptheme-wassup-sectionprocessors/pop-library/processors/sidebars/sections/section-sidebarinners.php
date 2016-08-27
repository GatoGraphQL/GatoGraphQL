<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_PROJECTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-projects'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_STORIES', PoP_ServerUtils::get_template_definition('sidebarinner-section-stories'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-announcements'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_DISCUSSIONS', PoP_ServerUtils::get_template_definition('sidebarinner-section-discussions'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_FEATURED', PoP_ServerUtils::get_template_definition('sidebarinner-section-featured'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_BLOG', PoP_ServerUtils::get_template_definition('sidebarinner-section-blog'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORPROJECTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-authorprojects'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORSTORIES', PoP_ServerUtils::get_template_definition('sidebarinner-section-authorstories'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-authorannouncements'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORDISCUSSIONS', PoP_ServerUtils::get_template_definition('sidebarinner-section-authordiscussions'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFEATURED', PoP_ServerUtils::get_template_definition('sidebarinner-section-authorfeatured'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYPROJECTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-myprojects'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYSTORIES', PoP_ServerUtils::get_template_definition('sidebarinner-section-mystories'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-myannouncements'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('sidebarinner-section-mydiscussions'));

class GD_Custom_Template_Processor_CustomSectionSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_SECTION_PROJECTS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_STORIES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_ANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_DISCUSSIONS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_FEATURED, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_BLOG, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORPROJECTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORSTORIES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORDISCUSSIONS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFEATURED,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYPROJECTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYSTORIES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYANNOUNCEMENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYDISCUSSIONS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_PROJECTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_PROJECTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_STORIES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_STORIES;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_ANNOUNCEMENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_ANNOUNCEMENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_DISCUSSIONS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_DISCUSSIONS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_FEATURED:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_FEATURED;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_BLOG:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_BLOG;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORPROJECTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORPROJECTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORSTORIES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORSTORIES;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORANNOUNCEMENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORANNOUNCEMENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORDISCUSSIONS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORDISCUSSIONS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFEATURED:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORFEATURED;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYPROJECTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYPROJECTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYSTORIES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYSTORIES;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYANNOUNCEMENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYANNOUNCEMENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYDISCUSSIONS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYDISCUSSIONS;
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CustomSectionSidebarInners();
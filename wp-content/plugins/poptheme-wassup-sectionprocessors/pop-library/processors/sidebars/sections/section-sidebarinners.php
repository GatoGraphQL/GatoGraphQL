<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_LOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-locationposts'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_STORIES', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-stories'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_ANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-announcements'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_DISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-discussions'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_FEATURED', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-featured'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_BLOG', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-blog'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TAGLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-taglocationposts'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TAGSTORIES', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-tagstories'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TAGANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-tagannouncements'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TAGDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-tagdiscussions'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFEATURED', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-tagfeatured'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-authorlocationposts'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORSTORIES', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-authorstories'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-authorannouncements'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-authordiscussions'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFEATURED', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-authorfeatured'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-mylocationposts'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYSTORIES', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-mystories'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-myannouncements'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-mydiscussions'));

class GD_Custom_Template_Processor_CustomSectionSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_SECTION_LOCATIONPOSTS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_STORIES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_ANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_DISCUSSIONS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_FEATURED, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_BLOG, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_TAGLOCATIONPOSTS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_TAGSTORIES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_TAGANNOUNCEMENTS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_TAGDISCUSSIONS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFEATURED, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORSTORIES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORDISCUSSIONS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFEATURED,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYLOCATIONPOSTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYSTORIES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYANNOUNCEMENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYDISCUSSIONS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_LOCATIONPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_LOCATIONPOSTS;
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
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_TAGLOCATIONPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGLOCATIONPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_TAGSTORIES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGSTORIES;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_TAGANNOUNCEMENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGANNOUNCEMENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_TAGDISCUSSIONS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGDISCUSSIONS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFEATURED:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGFEATURED;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORLOCATIONPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORLOCATIONPOSTS;
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

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYLOCATIONPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYLOCATIONPOSTS;
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
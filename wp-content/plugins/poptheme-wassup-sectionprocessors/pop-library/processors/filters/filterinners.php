<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_PROJECTS', PoP_ServerUtils::get_template_definition('filterinner-projects'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORPROJECTS', PoP_ServerUtils::get_template_definition('filterinner-authorprojects'));
define ('GD_TEMPLATE_FILTERINNER_STORIES', PoP_ServerUtils::get_template_definition('filterinner-stories'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORSTORIES', PoP_ServerUtils::get_template_definition('filterinner-authorstories'));
define ('GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('filterinner-announcements'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('filterinner-authorannouncements'));
define ('GD_TEMPLATE_FILTERINNER_DISCUSSIONS', PoP_ServerUtils::get_template_definition('filterinner-discussions'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS', PoP_ServerUtils::get_template_definition('filterinner-authordiscussions'));
define ('GD_TEMPLATE_FILTERINNER_FEATURED', PoP_ServerUtils::get_template_definition('filterinner-featured'));
define ('GD_TEMPLATE_FILTERINNER_BLOG', PoP_ServerUtils::get_template_definition('filterinner-blog'));
define ('GD_TEMPLATE_FILTERINNER_MYPROJECTS', PoP_ServerUtils::get_template_definition('filterinner-myprojects'));
define ('GD_TEMPLATE_FILTERINNER_MYSTORIES', PoP_ServerUtils::get_template_definition('filterinner-mystories'));
define ('GD_TEMPLATE_FILTERINNER_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('filterinner-myannouncements'));
define ('GD_TEMPLATE_FILTERINNER_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('filterinner-mydiscussions'));

class GD_Custom_Template_Processor_CustomFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_PROJECTS,
			GD_TEMPLATE_FILTERINNER_AUTHORPROJECTS,
			GD_TEMPLATE_FILTERINNER_STORIES,
			GD_TEMPLATE_FILTERINNER_AUTHORSTORIES,
			GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_DISCUSSIONS,
			GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_FILTERINNER_FEATURED,
			GD_TEMPLATE_FILTERINNER_BLOG,
			GD_TEMPLATE_FILTERINNER_MYPROJECTS,
			GD_TEMPLATE_FILTERINNER_MYSTORIES,
			GD_TEMPLATE_FILTERINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_MYDISCUSSIONS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_FILTERINNER_PROJECTS => GD_FILTER_PROJECTS,
			GD_TEMPLATE_FILTERINNER_AUTHORPROJECTS => GD_FILTER_AUTHORPROJECTS,
			GD_TEMPLATE_FILTERINNER_STORIES => GD_FILTER_STORIES,
			GD_TEMPLATE_FILTERINNER_AUTHORSTORIES => GD_FILTER_AUTHORSTORIES,
			GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS => GD_FILTER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS => GD_FILTER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_DISCUSSIONS => GD_FILTER_DISCUSSIONS,
			GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS => GD_FILTER_AUTHORDISCUSSIONS,			
			GD_TEMPLATE_FILTERINNER_FEATURED => GD_FILTER_FEATURED,				
			GD_TEMPLATE_FILTERINNER_BLOG => GD_FILTER_BLOG,
			GD_TEMPLATE_FILTERINNER_MYPROJECTS => GD_FILTER_MYPROJECTS,
			GD_TEMPLATE_FILTERINNER_MYSTORIES => GD_FILTER_MYSTORIES,
			GD_TEMPLATE_FILTERINNER_MYANNOUNCEMENTS => GD_FILTER_MYANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_MYDISCUSSIONS => GD_FILTER_MYDISCUSSIONS,
		);
		if ($filter = $filters[$template_id]) {

			return $filter;
		}
		
		return parent::get_filter($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CustomFilterInners();
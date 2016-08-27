<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('filter-announcements'));
define ('GD_TEMPLATE_FILTER_AUTHORANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('filter-authorannouncements'));
define ('GD_TEMPLATE_FILTER_AUTHORDISCUSSIONS', PoP_ServerUtils::get_template_definition('filter-authordiscussions'));
define ('GD_TEMPLATE_FILTER_AUTHORPROJECTS', PoP_ServerUtils::get_template_definition('filter-authorprojects'));
define ('GD_TEMPLATE_FILTER_AUTHORSTORIES', PoP_ServerUtils::get_template_definition('filter-authorstories'));
define ('GD_TEMPLATE_FILTER_BLOG', PoP_ServerUtils::get_template_definition('filter-blog'));
define ('GD_TEMPLATE_FILTER_DISCUSSIONS', PoP_ServerUtils::get_template_definition('filter-discussions'));
define ('GD_TEMPLATE_FILTER_FEATURED', PoP_ServerUtils::get_template_definition('filter-featured'));
define ('GD_TEMPLATE_FILTER_PROJECTS', PoP_ServerUtils::get_template_definition('filter-projects'));
define ('GD_TEMPLATE_FILTER_STORIES', PoP_ServerUtils::get_template_definition('filter-stories'));
define ('GD_TEMPLATE_FILTER_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('filter-myannouncements'));
define ('GD_TEMPLATE_FILTER_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('filter-mydiscussions'));
define ('GD_TEMPLATE_FILTER_MYPROJECTS', PoP_ServerUtils::get_template_definition('filter-myprojects'));
define ('GD_TEMPLATE_FILTER_MYSTORIES', PoP_ServerUtils::get_template_definition('filter-mystories'));

class GD_Custom_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_PROJECTS,
			GD_TEMPLATE_FILTER_AUTHORPROJECTS,
			GD_TEMPLATE_FILTER_STORIES,
			GD_TEMPLATE_FILTER_AUTHORSTORIES,
			GD_TEMPLATE_FILTER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_DISCUSSIONS,
			GD_TEMPLATE_FILTER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_FILTER_FEATURED,
			GD_TEMPLATE_FILTER_BLOG,
			GD_TEMPLATE_FILTER_MYPROJECTS,
			GD_TEMPLATE_FILTER_MYSTORIES,
			GD_TEMPLATE_FILTER_MYANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_MYDISCUSSIONS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_PROJECTS => GD_TEMPLATE_FILTERINNER_PROJECTS,
			GD_TEMPLATE_FILTER_AUTHORPROJECTS => GD_TEMPLATE_FILTERINNER_AUTHORPROJECTS,
			GD_TEMPLATE_FILTER_STORIES => GD_TEMPLATE_FILTERINNER_STORIES,
			GD_TEMPLATE_FILTER_AUTHORSTORIES => GD_TEMPLATE_FILTERINNER_AUTHORSTORIES,
			GD_TEMPLATE_FILTER_ANNOUNCEMENTS => GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_AUTHORANNOUNCEMENTS => GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_DISCUSSIONS => GD_TEMPLATE_FILTERINNER_DISCUSSIONS,
			GD_TEMPLATE_FILTER_AUTHORDISCUSSIONS => GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_FILTER_FEATURED => GD_TEMPLATE_FILTERINNER_FEATURED,
			GD_TEMPLATE_FILTER_BLOG => GD_TEMPLATE_FILTERINNER_BLOG,
			GD_TEMPLATE_FILTER_MYPROJECTS => GD_TEMPLATE_FILTERINNER_MYPROJECTS,
			GD_TEMPLATE_FILTER_MYSTORIES => GD_TEMPLATE_FILTERINNER_MYSTORIES,
			GD_TEMPLATE_FILTER_MYANNOUNCEMENTS => GD_TEMPLATE_FILTERINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_MYDISCUSSIONS => GD_TEMPLATE_FILTERINNER_MYDISCUSSIONS,
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
new GD_Custom_Template_Processor_CustomFilters();
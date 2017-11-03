<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_ANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filter-announcements'));
define ('GD_TEMPLATE_FILTER_AUTHORANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filter-authorannouncements'));
define ('GD_TEMPLATE_FILTER_TAGANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filter-tagannouncements'));
define ('GD_TEMPLATE_FILTER_AUTHORDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filter-authordiscussions'));
define ('GD_TEMPLATE_FILTER_TAGDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filter-tagdiscussions'));
define ('GD_TEMPLATE_FILTER_AUTHORLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-authorlocationposts'));
define ('GD_TEMPLATE_FILTER_TAGLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-taglocationposts'));
define ('GD_TEMPLATE_FILTER_AUTHORSTORIES', PoP_TemplateIDUtils::get_template_definition('filter-authorstories'));
define ('GD_TEMPLATE_FILTER_TAGSTORIES', PoP_TemplateIDUtils::get_template_definition('filter-tagstories'));
define ('GD_TEMPLATE_FILTER_BLOG', PoP_TemplateIDUtils::get_template_definition('filter-blog'));
define ('GD_TEMPLATE_FILTER_DISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filter-discussions'));
define ('GD_TEMPLATE_FILTER_FEATURED', PoP_TemplateIDUtils::get_template_definition('filter-featured'));
define ('GD_TEMPLATE_FILTER_TAGFEATURED', PoP_TemplateIDUtils::get_template_definition('filter-tagfeatured'));
define ('GD_TEMPLATE_FILTER_LOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-locationposts'));
define ('GD_TEMPLATE_FILTER_STORIES', PoP_TemplateIDUtils::get_template_definition('filter-stories'));
define ('GD_TEMPLATE_FILTER_MYANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filter-myannouncements'));
define ('GD_TEMPLATE_FILTER_MYDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filter-mydiscussions'));
define ('GD_TEMPLATE_FILTER_MYLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-mylocationposts'));
define ('GD_TEMPLATE_FILTER_MYSTORIES', PoP_TemplateIDUtils::get_template_definition('filter-mystories'));

class GD_Custom_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_LOCATIONPOSTS,
			GD_TEMPLATE_FILTER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_FILTER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_FILTER_STORIES,
			GD_TEMPLATE_FILTER_AUTHORSTORIES,
			GD_TEMPLATE_FILTER_TAGSTORIES,
			GD_TEMPLATE_FILTER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_DISCUSSIONS,
			GD_TEMPLATE_FILTER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_FILTER_TAGDISCUSSIONS,
			GD_TEMPLATE_FILTER_FEATURED,
			GD_TEMPLATE_FILTER_TAGFEATURED,
			GD_TEMPLATE_FILTER_BLOG,
			GD_TEMPLATE_FILTER_MYLOCATIONPOSTS,
			GD_TEMPLATE_FILTER_MYSTORIES,
			GD_TEMPLATE_FILTER_MYANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_MYDISCUSSIONS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_LOCATIONPOSTS => GD_TEMPLATE_FILTERINNER_LOCATIONPOSTS,
			GD_TEMPLATE_FILTER_AUTHORLOCATIONPOSTS => GD_TEMPLATE_FILTERINNER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_FILTER_TAGLOCATIONPOSTS => GD_TEMPLATE_FILTERINNER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_FILTER_STORIES => GD_TEMPLATE_FILTERINNER_STORIES,
			GD_TEMPLATE_FILTER_AUTHORSTORIES => GD_TEMPLATE_FILTERINNER_AUTHORSTORIES,
			GD_TEMPLATE_FILTER_TAGSTORIES => GD_TEMPLATE_FILTERINNER_TAGSTORIES,
			GD_TEMPLATE_FILTER_ANNOUNCEMENTS => GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_AUTHORANNOUNCEMENTS => GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_TAGANNOUNCEMENTS => GD_TEMPLATE_FILTERINNER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_FILTER_DISCUSSIONS => GD_TEMPLATE_FILTERINNER_DISCUSSIONS,
			GD_TEMPLATE_FILTER_AUTHORDISCUSSIONS => GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_FILTER_TAGDISCUSSIONS => GD_TEMPLATE_FILTERINNER_TAGDISCUSSIONS,
			GD_TEMPLATE_FILTER_FEATURED => GD_TEMPLATE_FILTERINNER_FEATURED,
			GD_TEMPLATE_FILTER_TAGFEATURED => GD_TEMPLATE_FILTERINNER_TAGFEATURED,
			GD_TEMPLATE_FILTER_BLOG => GD_TEMPLATE_FILTERINNER_BLOG,
			GD_TEMPLATE_FILTER_MYLOCATIONPOSTS => GD_TEMPLATE_FILTERINNER_MYLOCATIONPOSTS,
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
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_LOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filterinner-locationposts'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filterinner-authorlocationposts'));
define ('GD_TEMPLATE_FILTERINNER_TAGLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filterinner-taglocationposts'));
define ('GD_TEMPLATE_FILTERINNER_STORIES', PoP_TemplateIDUtils::get_template_definition('filterinner-stories'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORSTORIES', PoP_TemplateIDUtils::get_template_definition('filterinner-authorstories'));
define ('GD_TEMPLATE_FILTERINNER_TAGSTORIES', PoP_TemplateIDUtils::get_template_definition('filterinner-tagstories'));
define ('GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filterinner-announcements'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filterinner-authorannouncements'));
define ('GD_TEMPLATE_FILTERINNER_TAGANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filterinner-tagannouncements'));
define ('GD_TEMPLATE_FILTERINNER_DISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filterinner-discussions'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filterinner-authordiscussions'));
define ('GD_TEMPLATE_FILTERINNER_TAGDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filterinner-tagdiscussions'));
define ('GD_TEMPLATE_FILTERINNER_FEATURED', PoP_TemplateIDUtils::get_template_definition('filterinner-featured'));
define ('GD_TEMPLATE_FILTERINNER_TAGFEATURED', PoP_TemplateIDUtils::get_template_definition('filterinner-tagfeatured'));
define ('GD_TEMPLATE_FILTERINNER_BLOG', PoP_TemplateIDUtils::get_template_definition('filterinner-blog'));
define ('GD_TEMPLATE_FILTERINNER_MYLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('filterinner-mylocationposts'));
define ('GD_TEMPLATE_FILTERINNER_MYSTORIES', PoP_TemplateIDUtils::get_template_definition('filterinner-mystories'));
define ('GD_TEMPLATE_FILTERINNER_MYANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('filterinner-myannouncements'));
define ('GD_TEMPLATE_FILTERINNER_MYDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('filterinner-mydiscussions'));

class GD_Custom_Template_Processor_CustomFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_LOCATIONPOSTS,
			GD_TEMPLATE_FILTERINNER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_FILTERINNER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_FILTERINNER_STORIES,
			GD_TEMPLATE_FILTERINNER_AUTHORSTORIES,
			GD_TEMPLATE_FILTERINNER_TAGSTORIES,
			GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_DISCUSSIONS,
			GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_FILTERINNER_TAGDISCUSSIONS,
			GD_TEMPLATE_FILTERINNER_FEATURED,
			GD_TEMPLATE_FILTERINNER_TAGFEATURED,
			GD_TEMPLATE_FILTERINNER_BLOG,
			GD_TEMPLATE_FILTERINNER_MYLOCATIONPOSTS,
			GD_TEMPLATE_FILTERINNER_MYSTORIES,
			GD_TEMPLATE_FILTERINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_MYDISCUSSIONS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_FILTERINNER_LOCATIONPOSTS => GD_FILTER_LOCATIONPOSTS,
			GD_TEMPLATE_FILTERINNER_AUTHORLOCATIONPOSTS => GD_FILTER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_FILTERINNER_TAGLOCATIONPOSTS => GD_FILTER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_FILTERINNER_STORIES => GD_FILTER_STORIES,
			GD_TEMPLATE_FILTERINNER_AUTHORSTORIES => GD_FILTER_AUTHORSTORIES,
			GD_TEMPLATE_FILTERINNER_TAGSTORIES => GD_FILTER_TAGSTORIES,
			GD_TEMPLATE_FILTERINNER_ANNOUNCEMENTS => GD_FILTER_ANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_AUTHORANNOUNCEMENTS => GD_FILTER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_TAGANNOUNCEMENTS => GD_FILTER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_FILTERINNER_DISCUSSIONS => GD_FILTER_DISCUSSIONS,
			GD_TEMPLATE_FILTERINNER_AUTHORDISCUSSIONS => GD_FILTER_AUTHORDISCUSSIONS,			
			GD_TEMPLATE_FILTERINNER_TAGDISCUSSIONS => GD_FILTER_TAGDISCUSSIONS,			
			GD_TEMPLATE_FILTERINNER_FEATURED => GD_FILTER_FEATURED,				
			GD_TEMPLATE_FILTERINNER_TAGFEATURED => GD_FILTER_TAGFEATURED,				
			GD_TEMPLATE_FILTERINNER_BLOG => GD_FILTER_BLOG,
			GD_TEMPLATE_FILTERINNER_MYLOCATIONPOSTS => GD_FILTER_MYLOCATIONPOSTS,
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
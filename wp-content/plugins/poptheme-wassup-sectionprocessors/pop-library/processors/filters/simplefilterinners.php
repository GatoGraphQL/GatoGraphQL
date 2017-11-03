<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-locationposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-authorlocationposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_STORIES', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-stories'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORSTORIES', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-authorstories'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_ANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-announcements'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-authorannouncements'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_DISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-discussions'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-authordiscussions'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_FEATURED', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-featured'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_BLOG', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-blog'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-mylocationposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYSTORIES', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-mystories'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-myannouncements'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-mydiscussions'));

define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-taglocationposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGSTORIES', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-tagstories'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-tagannouncements'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-tagdiscussions'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGFEATURED', PoP_TemplateIDUtils::get_template_definition('simplefilterinner-tagfeatured'));

class PoPSP_Template_Processor_CustomSimpleFilterInners extends GD_Template_Processor_SimpleFilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_STORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORSTORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_DISCUSSIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_FEATURED,
			GD_TEMPLATE_SIMPLEFILTERINNER_BLOG,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGSTORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGDISCUSSIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGFEATURED,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYLOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYSTORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYDISCUSSIONS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONPOSTS => GD_FILTER_LOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORLOCATIONPOSTS => GD_FILTER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_STORIES => GD_FILTER_STORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORSTORIES => GD_FILTER_AUTHORSTORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_ANNOUNCEMENTS => GD_FILTER_ANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORANNOUNCEMENTS => GD_FILTER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_DISCUSSIONS => GD_FILTER_DISCUSSIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORDISCUSSIONS => GD_FILTER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_FEATURED => GD_FILTER_FEATURED,
			GD_TEMPLATE_SIMPLEFILTERINNER_BLOG => GD_FILTER_BLOG,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGLOCATIONPOSTS => GD_FILTER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGSTORIES => GD_FILTER_TAGSTORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGANNOUNCEMENTS => GD_FILTER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGDISCUSSIONS => GD_FILTER_TAGDISCUSSIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGFEATURED => GD_FILTER_TAGFEATURED,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYLOCATIONPOSTS => GD_FILTER_MYLOCATIONPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYSTORIES => GD_FILTER_MYSTORIES,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYANNOUNCEMENTS => GD_FILTER_MYANNOUNCEMENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYDISCUSSIONS => GD_FILTER_MYDISCUSSIONS,
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
new PoPSP_Template_Processor_CustomSimpleFilterInners();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorannouncements'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORDISCUSSIONS', PoP_ServerUtils::get_template_definition('delegatorfilter-authordiscussions'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorlocationposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORSTORIES', PoP_ServerUtils::get_template_definition('delegatorfilter-authorstories'));
define ('GD_TEMPLATE_DELEGATORFILTER_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('delegatorfilter-announcements'));
define ('GD_TEMPLATE_DELEGATORFILTER_BLOG', PoP_ServerUtils::get_template_definition('delegatorfilter-blog'));
define ('GD_TEMPLATE_DELEGATORFILTER_DISCUSSIONS', PoP_ServerUtils::get_template_definition('delegatorfilter-discussions'));
define ('GD_TEMPLATE_DELEGATORFILTER_FEATURED', PoP_ServerUtils::get_template_definition('delegatorfilter-featured'));
define ('GD_TEMPLATE_DELEGATORFILTER_LOCATIONPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-locationposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_STORIES', PoP_ServerUtils::get_template_definition('delegatorfilter-stories'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('delegatorfilter-tagannouncements'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGDISCUSSIONS', PoP_ServerUtils::get_template_definition('delegatorfilter-tagdiscussions'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGFEATURED', PoP_ServerUtils::get_template_definition('delegatorfilter-tagfeatured'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-taglocationposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGSTORIES', PoP_ServerUtils::get_template_definition('delegatorfilter-tagstories'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('delegatorfilter-myannouncements'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('delegatorfilter-mydiscussions'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-mylocationposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYSTORIES', PoP_ServerUtils::get_template_definition('delegatorfilter-mystories'));

class PoPSP_Template_Processor_CustomDelegatorFilters extends GD_Template_Processor_CustomDelegatorFiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DELEGATORFILTER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORSTORIES,
			GD_TEMPLATE_DELEGATORFILTER_ANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_BLOG,
			GD_TEMPLATE_DELEGATORFILTER_DISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_FEATURED,
			GD_TEMPLATE_DELEGATORFILTER_LOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_STORIES,
			GD_TEMPLATE_DELEGATORFILTER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGDISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_TAGFEATURED,
			GD_TEMPLATE_DELEGATORFILTER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGSTORIES,
			GD_TEMPLATE_DELEGATORFILTER_MYANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_MYDISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_MYLOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_MYSTORIES,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_DELEGATORFILTER_AUTHORANNOUNCEMENTS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORDISCUSSIONS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORDISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORLOCATIONPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORLOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORSTORIES => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORSTORIES,
			GD_TEMPLATE_DELEGATORFILTER_ANNOUNCEMENTS => GD_TEMPLATE_SIMPLEFILTERINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_BLOG => GD_TEMPLATE_SIMPLEFILTERINNER_BLOG,
			GD_TEMPLATE_DELEGATORFILTER_DISCUSSIONS => GD_TEMPLATE_SIMPLEFILTERINNER_DISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_FEATURED => GD_TEMPLATE_SIMPLEFILTERINNER_FEATURED,
			GD_TEMPLATE_DELEGATORFILTER_LOCATIONPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_STORIES => GD_TEMPLATE_SIMPLEFILTERINNER_STORIES,
			GD_TEMPLATE_DELEGATORFILTER_TAGANNOUNCEMENTS => GD_TEMPLATE_SIMPLEFILTERINNER_TAGANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGDISCUSSIONS => GD_TEMPLATE_SIMPLEFILTERINNER_TAGDISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_TAGFEATURED => GD_TEMPLATE_SIMPLEFILTERINNER_TAGFEATURED,
			GD_TEMPLATE_DELEGATORFILTER_TAGLOCATIONPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_TAGLOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGSTORIES => GD_TEMPLATE_SIMPLEFILTERINNER_TAGSTORIES,
			GD_TEMPLATE_DELEGATORFILTER_MYANNOUNCEMENTS => GD_TEMPLATE_SIMPLEFILTERINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_DELEGATORFILTER_MYDISCUSSIONS => GD_TEMPLATE_SIMPLEFILTERINNER_MYDISCUSSIONS,
			GD_TEMPLATE_DELEGATORFILTER_MYLOCATIONPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_MYLOCATIONPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_MYSTORIES => GD_TEMPLATE_SIMPLEFILTERINNER_MYSTORIES,
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
new PoPSP_Template_Processor_CustomDelegatorFilters();
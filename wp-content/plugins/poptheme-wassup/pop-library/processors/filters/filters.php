<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_WILDCARDTAGS', PoP_ServerUtils::get_template_definition('filter-wildcardtags'));
define ('GD_TEMPLATE_FILTER_WILDCARDPOSTS', PoP_ServerUtils::get_template_definition('filter-wildcardposts'));
define ('GD_TEMPLATE_FILTER_AUTHORWILDCARDPOSTS', PoP_ServerUtils::get_template_definition('filter-authorwildcardposts'));
define ('GD_TEMPLATE_FILTER_LINKS', PoP_ServerUtils::get_template_definition('filter-links'));
define ('GD_TEMPLATE_FILTER_AUTHORLINKS', PoP_ServerUtils::get_template_definition('filter-authorlinks'));
define ('GD_TEMPLATE_FILTER_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('filter-highlights'));
define ('GD_TEMPLATE_FILTER_AUTHORHIGHLIGHTS', PoP_ServerUtils::get_template_definition('filter-authorhighlights'));
define ('GD_TEMPLATE_FILTER_WEBPOSTS', PoP_ServerUtils::get_template_definition('filter-webposts'));
define ('GD_TEMPLATE_FILTER_AUTHORWEBPOSTS', PoP_ServerUtils::get_template_definition('filter-authorwebposts'));
define ('GD_TEMPLATE_FILTER_WILDCARDUSERS', PoP_ServerUtils::get_template_definition('filter-wildcardusers'));
define ('GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS', PoP_ServerUtils::get_template_definition('filter-authorwildcardusers'));
// define ('GD_TEMPLATE_FILTER_MYRESOURCES', PoP_ServerUtils::get_template_definition('filter-myresources'));
// define ('GD_TEMPLATE_FILTER_RESOURCES', PoP_ServerUtils::get_template_definition('filter-resources'));
define ('GD_TEMPLATE_FILTER_WILDCARDMYPOSTS', PoP_ServerUtils::get_template_definition('filter-wildcardmyposts'));
define ('GD_TEMPLATE_FILTER_MYLINKS', PoP_ServerUtils::get_template_definition('filter-mylinks'));
define ('GD_TEMPLATE_FILTER_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('filter-myhighlights'));
define ('GD_TEMPLATE_FILTER_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('filter-mywebposts'));
define ('GD_TEMPLATE_FILTER_TAGALLCONTENT', PoP_ServerUtils::get_template_definition('filter-tagallcontent'));

class GD_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_WILDCARDTAGS,
			GD_TEMPLATE_FILTER_WILDCARDPOSTS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_FILTER_LINKS,
			GD_TEMPLATE_FILTER_AUTHORLINKS,
			GD_TEMPLATE_FILTER_HIGHLIGHTS,
			GD_TEMPLATE_FILTER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_FILTER_WEBPOSTS,
			GD_TEMPLATE_FILTER_AUTHORWEBPOSTS,
			GD_TEMPLATE_FILTER_WILDCARDUSERS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS,
			// GD_TEMPLATE_FILTER_RESOURCES,
			GD_TEMPLATE_FILTER_WILDCARDMYPOSTS,
			// GD_TEMPLATE_FILTER_MYRESOURCES,
			GD_TEMPLATE_FILTER_MYLINKS,
			GD_TEMPLATE_FILTER_MYHIGHLIGHTS,
			GD_TEMPLATE_FILTER_MYWEBPOSTS,
			GD_TEMPLATE_FILTER_TAGALLCONTENT,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_WILDCARDTAGS => GD_TEMPLATE_FILTERINNER_WILDCARDTAGS,
			GD_TEMPLATE_FILTER_WILDCARDPOSTS => GD_TEMPLATE_FILTERINNER_WILDCARDPOSTS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDPOSTS => GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_FILTER_LINKS => GD_TEMPLATE_FILTERINNER_LINKS,
			GD_TEMPLATE_FILTER_AUTHORLINKS => GD_TEMPLATE_FILTERINNER_AUTHORLINKS,
			GD_TEMPLATE_FILTER_HIGHLIGHTS => GD_TEMPLATE_FILTERINNER_HIGHLIGHTS,
			GD_TEMPLATE_FILTER_AUTHORHIGHLIGHTS => GD_TEMPLATE_FILTERINNER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_FILTER_WEBPOSTS => GD_TEMPLATE_FILTERINNER_WEBPOSTS,
			GD_TEMPLATE_FILTER_AUTHORWEBPOSTS => GD_TEMPLATE_FILTERINNER_AUTHORWEBPOSTS,
			GD_TEMPLATE_FILTER_WILDCARDUSERS => GD_TEMPLATE_FILTERINNER_WILDCARDUSERS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS => GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDUSERS,
			// GD_TEMPLATE_FILTER_RESOURCES => GD_TEMPLATE_FILTERINNER_RESOURCES,
			GD_TEMPLATE_FILTER_WILDCARDMYPOSTS => GD_TEMPLATE_FILTERINNER_WILDCARDMYPOSTS,
			GD_TEMPLATE_FILTER_MYLINKS => GD_TEMPLATE_FILTERINNER_MYLINKS,
			GD_TEMPLATE_FILTER_MYHIGHLIGHTS => GD_TEMPLATE_FILTERINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_FILTER_MYWEBPOSTS => GD_TEMPLATE_FILTERINNER_MYWEBPOSTS,
			GD_TEMPLATE_FILTER_TAGALLCONTENT => GD_TEMPLATE_FILTERINNER_TAGALLCONTENT,
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
new GD_Template_Processor_CustomFilters();
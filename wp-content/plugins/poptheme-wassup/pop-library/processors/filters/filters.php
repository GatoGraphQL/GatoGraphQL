<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_WILDCARDTAGS', PoP_TemplateIDUtils::get_template_definition('filter-wildcardtags'));
define ('GD_TEMPLATE_FILTER_WILDCARDPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-wildcardposts'));
// define ('GD_TEMPLATE_FILTER_WILDCARDWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-wildcardwebposts'));
define ('GD_TEMPLATE_FILTER_AUTHORWILDCARDPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-authorwildcardposts'));
define ('GD_TEMPLATE_FILTER_TAGWILDCARDPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-tagwildcardposts'));
define ('GD_TEMPLATE_FILTER_LINKS', PoP_TemplateIDUtils::get_template_definition('filter-links'));
define ('GD_TEMPLATE_FILTER_AUTHORLINKS', PoP_TemplateIDUtils::get_template_definition('filter-authorlinks'));
define ('GD_TEMPLATE_FILTER_HIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('filter-highlights'));
define ('GD_TEMPLATE_FILTER_AUTHORHIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('filter-authorhighlights'));
define ('GD_TEMPLATE_FILTER_WEBPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-webposts'));
define ('GD_TEMPLATE_FILTER_POSTS', PoP_TemplateIDUtils::get_template_definition('filter-posts'));
define ('GD_TEMPLATE_FILTER_AUTHORWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-authorwebposts'));
define ('GD_TEMPLATE_FILTER_AUTHORPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-authorposts'));
define ('GD_TEMPLATE_FILTER_TAGWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-tagwebposts'));
define ('GD_TEMPLATE_FILTER_TAGPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-tagposts'));
define ('GD_TEMPLATE_FILTER_WILDCARDUSERS', PoP_TemplateIDUtils::get_template_definition('filter-wildcardusers'));
define ('GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS', PoP_TemplateIDUtils::get_template_definition('filter-authorwildcardusers'));
// define ('GD_TEMPLATE_FILTER_MYRESOURCES', PoP_TemplateIDUtils::get_template_definition('filter-myresources'));
// define ('GD_TEMPLATE_FILTER_RESOURCES', PoP_TemplateIDUtils::get_template_definition('filter-resources'));
define ('GD_TEMPLATE_FILTER_WILDCARDMYPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-wildcardmyposts'));
// define ('GD_TEMPLATE_FILTER_WILDCARDMYWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-wildcardmywebposts'));
define ('GD_TEMPLATE_FILTER_MYLINKS', PoP_TemplateIDUtils::get_template_definition('filter-mylinks'));
define ('GD_TEMPLATE_FILTER_MYHIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('filter-myhighlights'));
define ('GD_TEMPLATE_FILTER_MYWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-mywebposts'));
define ('GD_TEMPLATE_FILTER_MYPOSTS', PoP_TemplateIDUtils::get_template_definition('filter-myposts'));
// define ('GD_TEMPLATE_FILTER_TAGALLCONTENT', PoP_TemplateIDUtils::get_template_definition('filter-tagallcontent'));

class GD_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_WILDCARDTAGS,
			GD_TEMPLATE_FILTER_WILDCARDPOSTS,
			// GD_TEMPLATE_FILTER_WILDCARDWEBPOSTS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_FILTER_TAGWILDCARDPOSTS,
			GD_TEMPLATE_FILTER_LINKS,
			GD_TEMPLATE_FILTER_AUTHORLINKS,
			GD_TEMPLATE_FILTER_HIGHLIGHTS,
			GD_TEMPLATE_FILTER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_FILTER_WEBPOSTS,
			GD_TEMPLATE_FILTER_POSTS,
			GD_TEMPLATE_FILTER_AUTHORWEBPOSTS,
			GD_TEMPLATE_FILTER_AUTHORPOSTS,
			GD_TEMPLATE_FILTER_TAGWEBPOSTS,
			GD_TEMPLATE_FILTER_TAGPOSTS,
			GD_TEMPLATE_FILTER_WILDCARDUSERS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS,
			// GD_TEMPLATE_FILTER_RESOURCES,
			GD_TEMPLATE_FILTER_WILDCARDMYPOSTS,
			// GD_TEMPLATE_FILTER_WILDCARDMYWEBPOSTS,
			// GD_TEMPLATE_FILTER_MYRESOURCES,
			GD_TEMPLATE_FILTER_MYLINKS,
			GD_TEMPLATE_FILTER_MYHIGHLIGHTS,
			GD_TEMPLATE_FILTER_MYWEBPOSTS,
			GD_TEMPLATE_FILTER_MYPOSTS,
			// GD_TEMPLATE_FILTER_TAGALLCONTENT,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_WILDCARDTAGS => GD_TEMPLATE_FILTERINNER_WILDCARDTAGS,
			GD_TEMPLATE_FILTER_WILDCARDPOSTS => GD_TEMPLATE_FILTERINNER_WILDCARDPOSTS,
			// GD_TEMPLATE_FILTER_WILDCARDWEBPOSTS => GD_TEMPLATE_FILTERINNER_WILDCARDWEBPOSTS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDPOSTS => GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_FILTER_TAGWILDCARDPOSTS => GD_TEMPLATE_FILTERINNER_TAGWILDCARDPOSTS,
			GD_TEMPLATE_FILTER_LINKS => GD_TEMPLATE_FILTERINNER_LINKS,
			GD_TEMPLATE_FILTER_AUTHORLINKS => GD_TEMPLATE_FILTERINNER_AUTHORLINKS,
			GD_TEMPLATE_FILTER_HIGHLIGHTS => GD_TEMPLATE_FILTERINNER_HIGHLIGHTS,
			GD_TEMPLATE_FILTER_AUTHORHIGHLIGHTS => GD_TEMPLATE_FILTERINNER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_FILTER_WEBPOSTS => GD_TEMPLATE_FILTERINNER_WEBPOSTS,
			GD_TEMPLATE_FILTER_POSTS => GD_TEMPLATE_FILTERINNER_POSTS,
			GD_TEMPLATE_FILTER_AUTHORWEBPOSTS => GD_TEMPLATE_FILTERINNER_AUTHORWEBPOSTS,
			GD_TEMPLATE_FILTER_AUTHORPOSTS => GD_TEMPLATE_FILTERINNER_AUTHORPOSTS,
			GD_TEMPLATE_FILTER_TAGWEBPOSTS => GD_TEMPLATE_FILTERINNER_TAGWEBPOSTS,
			GD_TEMPLATE_FILTER_TAGPOSTS => GD_TEMPLATE_FILTERINNER_TAGPOSTS,
			GD_TEMPLATE_FILTER_WILDCARDUSERS => GD_TEMPLATE_FILTERINNER_WILDCARDUSERS,
			GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS => GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDUSERS,
			// GD_TEMPLATE_FILTER_RESOURCES => GD_TEMPLATE_FILTERINNER_RESOURCES,
			GD_TEMPLATE_FILTER_WILDCARDMYPOSTS => GD_TEMPLATE_FILTERINNER_WILDCARDMYPOSTS,
			// GD_TEMPLATE_FILTER_WILDCARDMYWEBPOSTS => GD_TEMPLATE_FILTERINNER_WILDCARDMYWEBPOSTS,
			GD_TEMPLATE_FILTER_MYLINKS => GD_TEMPLATE_FILTERINNER_MYLINKS,
			GD_TEMPLATE_FILTER_MYHIGHLIGHTS => GD_TEMPLATE_FILTERINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_FILTER_MYWEBPOSTS => GD_TEMPLATE_FILTERINNER_MYWEBPOSTS,
			GD_TEMPLATE_FILTER_MYPOSTS => GD_TEMPLATE_FILTERINNER_MYPOSTS,
			// GD_TEMPLATE_FILTER_TAGALLCONTENT => GD_TEMPLATE_FILTERINNER_TAGALLCONTENT,
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
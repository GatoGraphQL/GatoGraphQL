<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_WILDCARDTAGS', PoP_ServerUtils::get_template_definition('filterinner-wildcardtags'));
define ('GD_TEMPLATE_FILTERINNER_WILDCARDPOSTS', PoP_ServerUtils::get_template_definition('filterinner-wildcardposts'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDPOSTS', PoP_ServerUtils::get_template_definition('filterinner-authorwildcardposts'));
define ('GD_TEMPLATE_FILTERINNER_LINKS', PoP_ServerUtils::get_template_definition('filterinner-links'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORLINKS', PoP_ServerUtils::get_template_definition('filterinner-authorlinks'));
define ('GD_TEMPLATE_FILTERINNER_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('filterinner-highlights'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORHIGHLIGHTS', PoP_ServerUtils::get_template_definition('filterinner-authorhighlights'));
define ('GD_TEMPLATE_FILTERINNER_WEBPOSTS', PoP_ServerUtils::get_template_definition('filterinner-webposts'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORWEBPOSTS', PoP_ServerUtils::get_template_definition('filterinner-authorwebposts'));
define ('GD_TEMPLATE_FILTERINNER_WILDCARDUSERS', PoP_ServerUtils::get_template_definition('filterinner-wildcardusers'));
define ('GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDUSERS', PoP_ServerUtils::get_template_definition('filterinner-authorwildcardusers'));
define ('GD_TEMPLATE_FILTERINNER_WILDCARDMYPOSTS', PoP_ServerUtils::get_template_definition('filterinner-wildcardmyposts'));
define ('GD_TEMPLATE_FILTERINNER_MYLINKS', PoP_ServerUtils::get_template_definition('filterinner-mylinks'));
define ('GD_TEMPLATE_FILTERINNER_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('filterinner-myhighlights'));
define ('GD_TEMPLATE_FILTERINNER_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('filterinner-mywebposts'));
define ('GD_TEMPLATE_FILTERINNER_TAGALLCONTENT', PoP_ServerUtils::get_template_definition('filterinner-tagallcontent'));

class GD_Template_Processor_CustomFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_WILDCARDTAGS,
			GD_TEMPLATE_FILTERINNER_WILDCARDPOSTS,
			GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_FILTERINNER_LINKS,
			GD_TEMPLATE_FILTERINNER_AUTHORLINKS,
			GD_TEMPLATE_FILTERINNER_HIGHLIGHTS,
			GD_TEMPLATE_FILTERINNER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_FILTERINNER_WEBPOSTS,
			GD_TEMPLATE_FILTERINNER_AUTHORWEBPOSTS,
			GD_TEMPLATE_FILTERINNER_WILDCARDUSERS,
			GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDUSERS,
			GD_TEMPLATE_FILTERINNER_WILDCARDMYPOSTS,
			GD_TEMPLATE_FILTERINNER_MYLINKS,
			GD_TEMPLATE_FILTERINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_FILTERINNER_MYWEBPOSTS,
			GD_TEMPLATE_FILTERINNER_TAGALLCONTENT,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_FILTERINNER_WILDCARDTAGS => GD_FILTER_WILDCARDTAGS,
			GD_TEMPLATE_FILTERINNER_WILDCARDPOSTS => GD_FILTER_WILDCARDPOSTS,
			GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDPOSTS => GD_FILTER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_FILTERINNER_LINKS => GD_FILTER_LINKS,
			GD_TEMPLATE_FILTERINNER_AUTHORLINKS => GD_FILTER_AUTHORLINKS,
			GD_TEMPLATE_FILTERINNER_HIGHLIGHTS => GD_FILTER_HIGHLIGHTS,
			GD_TEMPLATE_FILTERINNER_AUTHORHIGHLIGHTS => GD_FILTER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_FILTERINNER_WEBPOSTS => GD_FILTER_WEBPOSTS,
			GD_TEMPLATE_FILTERINNER_AUTHORWEBPOSTS => GD_FILTER_AUTHORWEBPOSTS,
			GD_TEMPLATE_FILTERINNER_WILDCARDUSERS => GD_FILTER_WILDCARDUSERS,
			GD_TEMPLATE_FILTERINNER_AUTHORWILDCARDUSERS => GD_FILTER_AUTHORWILDCARDUSERS,
			GD_TEMPLATE_FILTERINNER_WILDCARDMYPOSTS => GD_FILTER_WILDCARDMYPOSTS,
			GD_TEMPLATE_FILTERINNER_MYLINKS => GD_FILTER_MYLINKS,
			GD_TEMPLATE_FILTERINNER_MYHIGHLIGHTS => GD_FILTER_MYHIGHLIGHTS,
			GD_TEMPLATE_FILTERINNER_MYWEBPOSTS => GD_FILTER_MYWEBPOSTS,
			GD_TEMPLATE_FILTERINNER_TAGALLCONTENT => GD_FILTER_TAGALLCONTENT,
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
new GD_Template_Processor_CustomFilterInners();
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDTAGS', PoP_ServerUtils::get_template_definition('simplefilterinner-wildcardtags'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-wildcardposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-authorwildcardposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTLINKS', PoP_ServerUtils::get_template_definition('simplefilterinner-webpostlinks'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTLINKS', PoP_ServerUtils::get_template_definition('simplefilterinner-authorwebpostlinks'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('simplefilterinner-highlights'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORHIGHLIGHTS', PoP_ServerUtils::get_template_definition('simplefilterinner-authorhighlights'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-webposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_POSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-posts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-authorwebposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGWEBPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-tagwebposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDUSERS', PoP_ServerUtils::get_template_definition('simplefilterinner-wildcardusers'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDUSERS', PoP_ServerUtils::get_template_definition('simplefilterinner-authorwildcardusers'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDMYPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-wildcardmyposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTLINKS', PoP_ServerUtils::get_template_definition('simplefilterinner-mywebpostlinks'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('simplefilterinner-myhighlights'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-mywebposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYPOSTS', PoP_ServerUtils::get_template_definition('simplefilterinner-myposts'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGALLCONTENT', PoP_ServerUtils::get_template_definition('simplefilterinner-tagallcontent'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_HOMEALLCONTENT', PoP_ServerUtils::get_template_definition('simplefilterinner-homeallcontent'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORMAINALLCONTENT', PoP_ServerUtils::get_template_definition('simplefilterinner-authormainallcontent'));
// define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORALLCONTENT', PoP_ServerUtils::get_template_definition('simplefilterinner-authorallcontent'));

class GD_Template_Processor_CustomSimpleFilterInners extends GD_Template_Processor_SimpleFilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDTAGS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTLINKS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTLINKS,
			GD_TEMPLATE_SIMPLEFILTERINNER_HIGHLIGHTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_POSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGWEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDUSERS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDUSERS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDMYPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTLINKS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGALLCONTENT,
			GD_TEMPLATE_SIMPLEFILTERINNER_HOMEALLCONTENT,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORMAINALLCONTENT,
			// GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORALLCONTENT,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDTAGS => GD_FILTER_WILDCARDTAGS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDPOSTS => GD_FILTER_WILDCARDPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDPOSTS => GD_FILTER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTLINKS => GD_FILTER_LINKS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTLINKS => GD_FILTER_AUTHORLINKS,
			GD_TEMPLATE_SIMPLEFILTERINNER_HIGHLIGHTS => GD_FILTER_HIGHLIGHTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORHIGHLIGHTS => GD_FILTER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTS => GD_FILTER_WEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_POSTS => GD_FILTER_POSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTS => GD_FILTER_AUTHORWEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGWEBPOSTS => GD_FILTER_TAGWEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDUSERS => GD_FILTER_WILDCARDUSERS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDUSERS => GD_FILTER_AUTHORWILDCARDUSERS,
			GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDMYPOSTS => GD_FILTER_WILDCARDMYPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTLINKS => GD_FILTER_MYLINKS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYHIGHLIGHTS => GD_FILTER_MYHIGHLIGHTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTS => GD_FILTER_MYWEBPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYPOSTS => GD_FILTER_MYPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGALLCONTENT => GD_FILTER_TAGWILDCARDPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_HOMEALLCONTENT => GD_FILTER_WILDCARDPOSTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORMAINALLCONTENT => GD_FILTER_AUTHORWILDCARDPOSTS,
			// GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORALLCONTENT => GD_FILTER_AUTHORWILDCARDPOSTS,
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
new GD_Template_Processor_CustomSimpleFilterInners();
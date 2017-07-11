<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DELEGATORFILTER_WILDCARDTAGS', PoP_ServerUtils::get_template_definition('delegatorfilter-wildcardtags'));
define ('GD_TEMPLATE_DELEGATORFILTER_WILDCARDPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-wildcardposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorwildcardposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_WEBPOSTLINKS', PoP_ServerUtils::get_template_definition('delegatorfilter-webpostlinks'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORWEBPOSTLINKS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorwebpostlinks'));
define ('GD_TEMPLATE_DELEGATORFILTER_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('delegatorfilter-highlights'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORHIGHLIGHTS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorhighlights'));
define ('GD_TEMPLATE_DELEGATORFILTER_WEBPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-webposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_POSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-posts'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORWEBPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorwebposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGWEBPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-tagwebposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-tagposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_WILDCARDUSERS', PoP_ServerUtils::get_template_definition('delegatorfilter-wildcardusers'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDUSERS', PoP_ServerUtils::get_template_definition('delegatorfilter-authorwildcardusers'));
define ('GD_TEMPLATE_DELEGATORFILTER_WILDCARDMYPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-wildcardmyposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTLINKS', PoP_ServerUtils::get_template_definition('delegatorfilter-mywebpostlinks'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('delegatorfilter-myhighlights'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-mywebposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYPOSTS', PoP_ServerUtils::get_template_definition('delegatorfilter-myposts'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGMAINALLCONTENT', PoP_ServerUtils::get_template_definition('delegatorfilter-tagmainallcontent'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGALLCONTENT', PoP_ServerUtils::get_template_definition('delegatorfilter-tagallcontent'));
define ('GD_TEMPLATE_DELEGATORFILTER_HOMEALLCONTENT', PoP_ServerUtils::get_template_definition('delegatorfilter-homeallcontent'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORMAINALLCONTENT', PoP_ServerUtils::get_template_definition('delegatorfilter-authormainallcontent'));
// define ('GD_TEMPLATE_DELEGATORFILTER_AUTHORALLCONTENT', PoP_ServerUtils::get_template_definition('delegatorfilter-authorallcontent'));

class GD_Template_Processor_CustomDelegatorFilters extends GD_Template_Processor_CustomDelegatorFiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDTAGS,
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_WEBPOSTLINKS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWEBPOSTLINKS,
			GD_TEMPLATE_DELEGATORFILTER_HIGHLIGHTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_DELEGATORFILTER_WEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_POSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGWEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDUSERS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDUSERS,
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDMYPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTLINKS,
			GD_TEMPLATE_DELEGATORFILTER_MYHIGHLIGHTS,
			GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_MYPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGMAINALLCONTENT,
			GD_TEMPLATE_DELEGATORFILTER_TAGALLCONTENT,
			GD_TEMPLATE_DELEGATORFILTER_HOMEALLCONTENT,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORMAINALLCONTENT,
			// GD_TEMPLATE_DELEGATORFILTER_AUTHORALLCONTENT,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDTAGS => GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDTAGS,
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_WEBPOSTLINKS => GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTLINKS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWEBPOSTLINKS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTLINKS,
			GD_TEMPLATE_DELEGATORFILTER_HIGHLIGHTS => GD_TEMPLATE_SIMPLEFILTERINNER_HIGHLIGHTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORHIGHLIGHTS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORHIGHLIGHTS,
			GD_TEMPLATE_DELEGATORFILTER_WEBPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_WEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_POSTS => GD_TEMPLATE_SIMPLEFILTERINNER_POSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWEBPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGWEBPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_TAGWEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_TAGPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDUSERS => GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDUSERS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDUSERS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORWILDCARDUSERS,
			GD_TEMPLATE_DELEGATORFILTER_WILDCARDMYPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_WILDCARDMYPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTLINKS => GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTLINKS,
			GD_TEMPLATE_DELEGATORFILTER_MYHIGHLIGHTS => GD_TEMPLATE_SIMPLEFILTERINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_MYWEBPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_MYPOSTS => GD_TEMPLATE_SIMPLEFILTERINNER_MYPOSTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGMAINALLCONTENT => GD_TEMPLATE_SIMPLEFILTERINNER_TAGALLCONTENT,
			GD_TEMPLATE_DELEGATORFILTER_TAGALLCONTENT => GD_TEMPLATE_SIMPLEFILTERINNER_TAGALLCONTENT,
			GD_TEMPLATE_DELEGATORFILTER_HOMEALLCONTENT => GD_TEMPLATE_SIMPLEFILTERINNER_HOMEALLCONTENT,
			GD_TEMPLATE_DELEGATORFILTER_AUTHORMAINALLCONTENT => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORMAINALLCONTENT,
			// GD_TEMPLATE_DELEGATORFILTER_AUTHORALLCONTENT => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHORALLCONTENT,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}
	
		return parent::get_inner_template($template_id);
	}

	function get_block_target($template_id, $atts) {

		// Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
		// Comment Leo 12/01/2017: Actually, for the forms we must use .active instead of :last-child, because the selector is executed
		// on runtime, and not when initializing the JS
		switch ($template_id) {
			
			// Because the Home has a different structure (blockgroup_home => block with content) then must change the block target
			case GD_TEMPLATE_DELEGATORFILTER_HOMEALLCONTENT:
				
				return '#'.GD_TEMPLATEID_PAGESECTIONID_MAIN.' .pop-pagesection-page.toplevel.active > .blockgroup-home > .blocksection-extensions > .pop-block.withfilter';
			
			// Because the Home has a different structure (blockgroup_home => block with content) then must change the block target
			case GD_TEMPLATE_DELEGATORFILTER_AUTHORMAINALLCONTENT:
				
				return '#'.GD_TEMPLATEID_PAGESECTIONID_MAIN.' .pop-pagesection-page.toplevel.active > .blockgroup-author > .blocksection-extensions > .pop-block.withfilter';

			case GD_TEMPLATE_DELEGATORFILTER_TAGMAINALLCONTENT:
				
				return '#'.GD_TEMPLATEID_PAGESECTIONID_MAIN.' .pop-pagesection-page.toplevel.active > .blockgroup-tag > .blocksection-extensions > .pop-block.withfilter';
		}

		return parent::get_block_target($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomDelegatorFilters();
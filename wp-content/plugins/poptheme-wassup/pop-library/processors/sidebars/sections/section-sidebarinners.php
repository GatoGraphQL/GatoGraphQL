<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHPOSTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-searchposts'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebarinner-section-allcontent'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTLINKS', PoP_ServerUtils::get_template_definition('sidebarinner-section-webpostlinks'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-highlights'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-webposts'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHUSERS', PoP_ServerUtils::get_template_definition('sidebarinner-section-searchusers'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS', PoP_ServerUtils::get_template_definition('sidebarinner-section-allusers'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS_NOFILTER', PoP_ServerUtils::get_template_definition('sidebarinner-section-allusers-nofilter'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TRENDINGTAGS', PoP_ServerUtils::get_template_definition('sidebarinner-section-trendingtags'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TAGS', PoP_ServerUtils::get_template_definition('sidebarinner-section-tags'));

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYCONTENT', PoP_ServerUtils::get_template_definition('sidebarinner-section-mycontent'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTLINKS', PoP_ServerUtils::get_template_definition('sidebarinner-section-mywebpostlinks'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-myhighlights'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-mywebposts'));

define ('GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_MAINALLCONTENT', PoP_ServerUtils::get_template_definition('sidebarinner-authorsection-mainallcontent'));
define ('GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebarinner-authorsection-allcontent'));
define ('GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLUSERS', PoP_ServerUtils::get_template_definition('sidebarinner-authorsection-allusers'));

define ('GD_TEMPLATE_SIDEBARINNER_TAGSECTION_MAINALLCONTENT', PoP_ServerUtils::get_template_definition('sidebarinner-tagsection-mainallcontent'));
define ('GD_TEMPLATE_SIDEBARINNER_TAGSECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebarinner-tagsection-allcontent'));
define ('GD_TEMPLATE_SIDEBARINNER_TAGSECTION_WEBPOSTS', PoP_ServerUtils::get_template_definition('sidebarinner-tagsection-webposts'));

define ('GD_TEMPLATE_SIDEBARINNER_HOMESECTION_ALLCONTENT', PoP_ServerUtils::get_template_definition('sidebarinner-homesection-allcontent'));

class GD_Template_Processor_CustomSectionSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHPOSTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTLINKS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_HIGHLIGHTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHUSERS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS_NOFILTER,
			GD_TEMPLATE_SIDEBARINNER_SECTION_TRENDINGTAGS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_TAGS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYCONTENT,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTLINKS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYHIGHLIGHTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTS,
			GD_TEMPLATE_SIDEBARINNER_TAGSECTION_MAINALLCONTENT,
			GD_TEMPLATE_SIDEBARINNER_TAGSECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBARINNER_TAGSECTION_WEBPOSTS,
			GD_TEMPLATE_SIDEBARINNER_HOMESECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_MAINALLCONTENT,
			GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLCONTENT,
			GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLUSERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			// Trending Tags has no filter
			case GD_TEMPLATE_SIDEBARINNER_SECTION_TRENDINGTAGS:
				
				$ret[] = GD_TEMPLATE_CODE_TRENDINGTAGSDESCRIPTION;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_TAGS:

				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WILDCARDTAGS;
				break;
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;//GD_TEMPLATE_BUTTONGROUP_SEARCHPOSTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WILDCARDPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_ALLCONTENT:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;//GD_TEMPLATE_BUTTONGROUP_ALLCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WILDCARDPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTLINKS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;//GD_TEMPLATE_BUTTONGROUP_WEBPOSTLINKS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WEBPOSTLINKS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_HIGHLIGHTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_HIGHLIGHTS;//GD_TEMPLATE_BUTTONGROUP_HIGHLIGHTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_HIGHLIGHTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_WEBPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTION;//GD_TEMPLATE_BUTTONGROUP_WEBPOSTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WEBPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_SEARCHUSERS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_USERS;//GD_TEMPLATE_BUTTONGROUP_SEARCHUSERS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WILDCARDUSERS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_USERS;//GD_TEMPLATE_BUTTONGROUP_ALLUSERS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WILDCARDUSERS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_ALLUSERS_NOFILTER:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_USERS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYCONTENT:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_WILDCARDMYPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTLINKS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;//GD_TEMPLATE_BUTTONGROUP_MYWEBPOSTLINKS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTLINKS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYHIGHLIGHTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYHIGHLIGHTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYHIGHLIGHTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYWEBPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;//GD_TEMPLATE_BUTTONGROUP_MYWEBPOSTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYWEBPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_TAGSECTION_MAINALLCONTENT:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGMAINALLCONTENT;
				break;

			case GD_TEMPLATE_SIDEBARINNER_TAGSECTION_ALLCONTENT:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGALLCONTENT;
				break;

			case GD_TEMPLATE_SIDEBARINNER_TAGSECTION_WEBPOSTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGWEBPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_HOMESECTION_ALLCONTENT:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_HOMESECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_HOMEALLCONTENT;
				break;

			case GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_MAINALLCONTENT:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORMAINALLCONTENT;
				break;

			case GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLCONTENT:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDPOSTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_ALLUSERS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORUSERS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORWILDCARDUSERS;
				break;

		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomSectionSidebarInners();
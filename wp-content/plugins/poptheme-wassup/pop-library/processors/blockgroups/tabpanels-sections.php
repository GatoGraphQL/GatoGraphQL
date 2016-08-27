<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Sections
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS', PoP_ServerUtils::get_template_definition('blockgroup-searchposts-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT', PoP_ServerUtils::get_template_definition('blockgroup-homeallcontent-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT', PoP_ServerUtils::get_template_definition('blockgroup-tagallcontent-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT', PoP_ServerUtils::get_template_definition('blockgroup-allcontent-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS', PoP_ServerUtils::get_template_definition('blockgroup-links-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('blockgroup-highlights-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS', PoP_ServerUtils::get_template_definition('blockgroup-webposts-tabpanel'));

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS', PoP_ServerUtils::get_template_definition('blockgroup-searchusers-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS', PoP_ServerUtils::get_template_definition('blockgroup-allusers-tabpanel'));

// My Content
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT', PoP_ServerUtils::get_template_definition('blockgroup-mycontent-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS', PoP_ServerUtils::get_template_definition('blockgroup-mylinks-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('blockgroup-myhighlights-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('blockgroup-mywebposts-tabpanel'));

class GD_Template_Processor_SectionTabPanelBlockGroups extends GD_Template_Processor_SectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS,
			
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS,
			
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS:

				// Only reload/destroy if these are main blocks
				if ($this->get_att($template_id, $atts, 'is-mainblock')) {
					$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
					$this->add_jsmethod($ret, 'refetchBlockGroupOnUserLoggedIn');
				}
				break;
		}

		return $ret;
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT:

				return gd_navigation_menu_item(POP_WPAPI_PAGE_ALLCONTENT, true).__('Latest content', 'poptheme-wassup');

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT:

				return gd_navigation_menu_item(POP_WPAPI_PAGE_ALLCONTENT, true).sprintf(
					__('Content tagged with “#%s”', 'poptheme-wassup'),
					single_tag_title('', false)
				);
		}
		
		return parent::get_title($template_id);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW,
						// GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_ALLUSERS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYCONTENT_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYLINKS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYWEBPOSTS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;
		}

		// Allow Events Manager to add the Map format
		$ret = apply_filters('GD_Template_Processor_SectionTabPanelBlockGroups:blocks', $ret, $template_id);

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT:

				$ret = array(
					GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT:

				$ret = array(
					GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS:

				$ret = array(
					GD_TEMPLATE_BLOCK_LINKS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_LINKS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_LINKS_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS:

				$ret = array(
					GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_LIST,
					),
					// GD_TEMPLATE_BLOCK_ALLUSERS_SCROLLMAP,
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS:

				$ret = array(
					GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_LIST,
					),
					// GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLLMAP,
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT:

				$ret = array(
					GD_TEMPLATE_BLOCK_MYCONTENT_TABLE_EDIT => array(),
					GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW => array(
						GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS:

				$ret = array(
					GD_TEMPLATE_BLOCK_MYLINKS_TABLE_EDIT => array(),
					GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => array(
						GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW,
					),	
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_MYWEBPOSTS_TABLE_EDIT => array(),
					GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_SIMPLEVIEWPREVIEW => array(
						GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_FULLVIEWPREVIEW,
					),
				);
				break;
		}

		if ($ret) {
			return apply_filters('GD_Template_Processor_SectionTabPanelBlockGroups:panel_headers', $ret, $template_id);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	protected function get_controlgroup_top($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS:
				
					return GD_TEMPLATE_CONTROLGROUP_POSTLIST;
				
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS:

					return GD_TEMPLATE_CONTROLGROUP_USERLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS:
				
					return GD_TEMPLATE_CONTROLGROUP_MYPOSTLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS:
				
					return GD_TEMPLATE_CONTROLGROUP_MYWEBPOSTLIST;
			}
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT:
				
				return GD_TEMPLATE_FILTER_WILDCARDPOSTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT:
				
				return GD_TEMPLATE_FILTER_TAGALLCONTENT;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS:
				
				return GD_TEMPLATE_FILTER_LINKS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS:
				
				return GD_TEMPLATE_FILTER_HIGHLIGHTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS:
				
				return GD_TEMPLATE_FILTER_WEBPOSTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS:

				return GD_TEMPLATE_FILTER_WILDCARDUSERS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT:

				return GD_TEMPLATE_FILTER_WILDCARDMYPOSTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS:
				
				return GD_TEMPLATE_FILTER_MYLINKS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS:
				
				return GD_TEMPLATE_FILTER_MYHIGHLIGHTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS:
				
				return GD_TEMPLATE_FILTER_MYWEBPOSTS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_DETAILS,
			// GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_DETAILS,
			
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_FULLVIEW,
			
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_THUMBNAIL,
			
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL,
		);
		$grids = array(
			GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_LIST,
			
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYCONTENT_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYLINKS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYWEBPOSTS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_FULLVIEWPREVIEW,
		);

		if (in_array($blockunit, $details)) {

			return 'fa-th-list';
		}
		elseif (in_array($blockunit, $simpleviews) || in_array($blockunit, $simpleviewpreviews)) {
			
			return 'fa-angle-right';
		}
		elseif (in_array($blockunit, $fullviews) || in_array($blockunit, $fullviewpreviews)) {
			
			return 'fa-angle-double-right';
		}
		elseif (in_array($blockunit, $thumbnails) || in_array($blockunit, $grids)) {
			
			return 'fa-th';
		}
		elseif (in_array($blockunit, $lists)) {
			
			return 'fa-list';
		}
		elseif (in_array($blockunit, $maps)) {
			
			return 'fa-map-marker';
		}
		elseif (in_array($blockunit, $edits)) {
			
			return 'fa-edit';
		}
		// elseif (in_array($blockunit, $fullviewpreviews)) {
			
		// 	return 'fa-eye';
		// }

		return parent::get_panel_header_fontawesome($blockgroup, $blockunit);
	}
	function get_panel_header_title($blockgroup, $blockunit) {
	// function get_panel_header_tooltip($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_DETAILS,
			// GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_DETAILS,
			
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_FULLVIEW,
		);
		$onlyfullviews = array(
			GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_THUMBNAIL,
			
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL,
		);
		$grids = array(
			GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_LINKS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_LIST,
			
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_ALLUSERS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYCONTENT_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYLINKS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYWEBPOSTS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_FULLVIEWPREVIEW,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $simpleviews) || in_array($blockunit, $simpleviewpreviews)) {
			
			return __('Feed', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $fullviews) || in_array($blockunit, $fullviewpreviews)) {
			
			return __('Extended', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $onlyfullviews)) {
			
			return __('Full view', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return __('Thumbnail', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $grids)) {
			
			return __('Grid', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $lists)) {
			
			return __('List', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $maps)) {
			
			return __('Map', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $edits)) {
			
			return __('Edit', 'poptheme-wassup');
		}
		// elseif (in_array($blockunit, $fullviewpreviews)) {
			
		// 	return __('View/Preview', 'poptheme-wassup');
		// }

		return parent::get_panel_header_title($blockgroup, $blockunit);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT:

				$this->append_att($template_id, $atts, 'class', 'pop-home-latesteverything');
				break;
		}

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS,
			
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS,
		);
		$tables = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS,
		);
		if (in_array($template_id, $feeds)) {
			$class = 'feed';
		}
		elseif (in_array($template_id, $tables)) {
			$class = 'tableblock';
		}
		if ($class) {
			$this->append_att($template_id, $atts, 'class', $class);
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SectionTabPanelBlockGroups();

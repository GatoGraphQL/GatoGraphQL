<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-singlerelatedcontent'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-singlerelatedhighlightcontent'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-singleauthors'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-singlerecommendedby'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-singleupvotedby'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-singledownvotedby'));

class GD_Template_Processor_SingleSectionTabPanelBlockGroups extends GD_Template_Processor_DefaultActiveTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:

				$ret = array_merge(
					$ret,
					array(
						// GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW,
						// GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
					)
				);
				break;
		}

		// Allow Events Manager to add the Map format
		$ret = apply_filters('GD_Template_Processor_SingleSectionTabPanelBlockGroups:blocks', $ret, $template_id);

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:

				$ret = array(
					GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:

				$ret = array(
					GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST => array(),
					GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL => array(),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:

				$ret = array(
					GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_LIST,
					),
					// GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLLMAP,
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:

				$ret = array(
					GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST,
					),
					// GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:

				$ret = array(
					GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST,
					),
					// GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				$ret = array(
					GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST,
					),
					// GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
				);
				break;
		}

		if ($ret) {
			return apply_filters('GD_Template_Processor_SingleSectionTabPanelBlockGroups:panel_headers', $ret, $template_id);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	function intercept($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				return true;
		}

		return parent::intercept($template_id);
	}

	// function get_intercept_settings($template_id, $atts) {

	// 	$ret = parent::get_intercept_settings($template_id, $atts);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

	// 			$ret[] = GD_INTERCEPTOR_WITHPARAMS;
	// 			break;
	// 	}

	// 	return $ret;
	// }

	protected function get_controlgroup_bottom($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:

					$post = $vars['global-state']['post']/*global $post*/;
					if (get_post_status($post->ID) == 'publish')  {
						
						return GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLIST;
					}
					break;
			
				// Single Authors has no filter, so show only the Share control
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:

					$post = $vars['global-state']['post']/*global $post*/;
					if (get_post_status($post->ID) == 'publish')  {
						
						return GD_TEMPLATE_CONTROLGROUP_SUBMENUSHARE;
					}
					break;
					
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

					$post = $vars['global-state']['post']/*global $post*/;
					if (get_post_status($post->ID) == 'publish')  {
						
						return GD_TEMPLATE_CONTROLGROUP_SUBMENUUSERLIST;
					}
					break;
			}
		}

		return parent::get_controlgroup_bottom($template_id);
	}

	// protected function get_blocksections_classes($template_id) {

	// 	$ret = parent::get_blocksections_classes($template_id);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:			
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

	// 			$ret['controlgroup-top'] = 'top pull-right';
	// 			break;
	// 	}

	// 	return $ret;
	// }

	// protected function get_controlgroup_top($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:			
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

	// 			return GD_TEMPLATE_CONTROLGROUP_BODYITEM;
	// 	}

	// 	return parent::get_controlgroup_top($template_id);
	// }

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
				
				return GD_TEMPLATE_FILTER_WILDCARDPOSTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
				
				return GD_TEMPLATE_FILTER_HIGHLIGHTS;
		
			// Single Authors has no filter, because the authors are provided using 'include' which can't be filtered
			// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				return GD_TEMPLATE_FILTER_WILDCARDUSERS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				return GD_Template_Processor_CustomSectionBlocksUtils::get_single_title();
		}
		
		return parent::get_title($template_id);
	}

	function get_submenu($template_id) {

		// Do not add for the quickview
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:

				// Comment Leo 09/11/2015: No need to add this information for the Upvote/Downvote, it's too much
				// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
				// case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

					if ($submenu = GD_Template_Processor_CustomSectionBlocksUtils::get_single_submenu()) {
						return $submenu;
					}
					break;
			}
		}
		
		return parent::get_submenu($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS,
			// GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL,
		);
		$grids = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
		);
		
		if (in_array($blockunit, $details)) {

			return 'fa-th-list';
		}
		elseif (in_array($blockunit, $simpleviews)) {
			
			return 'fa-angle-right';
		}
		elseif (in_array($blockunit, $fullviews)) {
			
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

		return parent::get_panel_header_fontawesome($blockgroup, $blockunit);
	}
	function get_panel_header_title($blockgroup, $blockunit, $atts) {

		$details = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS,
			// GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW,
		);
		$onlyfullviews = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL,
		);
		$grids = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
			GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
		);
		
		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $simpleviews)) {
			
			return __('Feed', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $fullviews)) {
			
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

		return parent::get_panel_header_title($blockgroup, $blockunit, $atts);
	}
	function get_panel_header_tooltip($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:
				
				return $this->get_panel_header_title($blockgroup, $blockunit, $atts);
		}

		return parent::get_panel_header_tooltip($blockgroup, $blockunit);
	}
	// function show_panel_header_title($blockgroup, $blockunit) {

	// 	switch ($blockgroup) {

	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

	// 			return false;
	// 	}

	// 	return parent::show_panel_header_title($blockgroup, $blockunit);
	// }

	function is_active_blockunit($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				global $gd_template_settingsmanager;
				if ($page_id = GD_TemplateManager_Utils::get_hierarchy_page_id()) {
					return ($gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_SINGLE) == $blockunit);
				}
				break;
		}
	
		return parent::is_active_blockunit($blockgroup, $blockunit);
	}

	function get_default_active_blockunit($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				$pages_id = array(
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT => POP_COREPROCESSORS_PAGE_RELATEDCONTENT,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT => POPTHEME_WASSUP_PAGE_HIGHLIGHTS,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS => POP_COREPROCESSORS_PAGE_POSTAUTHORS,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY => POP_COREPROCESSORS_PAGE_RECOMMENDEDBY,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY => POP_COREPROCESSORS_PAGE_UPVOTEDBY,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY => POP_COREPROCESSORS_PAGE_DOWNVOTEDBY,
				);
				$page_id = $pages_id[$template_id];

				global $gd_template_settingsmanager;
				return $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_SINGLE);
		}
	
		return parent::get_default_active_blockunit($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		global $gd_template_settingsmanager;

		// Set lazy for the blocks
		$active_blockunit = $this->get_active_blockunit($blockgroup, true);
		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				// Hide the Title, Controls, Filter
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');		
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'filter-hidden', true);	
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'show-controls', false);	

				// Do not load the content if not showing it initially
				if ($active_blockunit != $blockgroup_block) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);		
				}
				break;

		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY:

				// Hide the tab title
				$this->append_att($template_id, $atts, 'class', 'pop-tabtitle-hidden');
				break;
		}

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY,
		);
		if (in_array($template_id, $feeds)) {
			$class = 'feed';
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
new GD_Template_Processor_SingleSectionTabPanelBlockGroups();

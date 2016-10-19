<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagprojects'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagstories'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagannouncements'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagdiscussions'));

class GD_Custom_Template_Processor_TagSectionTabPanelBlockGroups extends GD_Template_Processor_TagSectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_LIST,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLLMAP => array(),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_LIST,
					),
				);
				break;
		}

		if ($ret) {
			return apply_filters('GD_Custom_Template_Processor_TagSectionTabPanelBlockGroups:panel_headers', $ret, $template_id);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS:

				return GD_TEMPLATE_FILTER_TAGPROJECTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES:
			
				return GD_TEMPLATE_FILTER_TAGSTORIES;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS:
			
				return GD_TEMPLATE_FILTER_TAGANNOUNCEMENTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS:
			
				return GD_TEMPLATE_FILTER_TAGDISCUSSIONS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLLMAP,
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
		elseif (in_array($blockunit, $thumbnails)) {
			
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
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGSTORIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGANNOUNCEMENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGDISCUSSIONS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLLMAP,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $simpleviews)) {
			
			return __('Feed', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $fullviews)) {
			
			return __('Extended', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return __('Thumbnail', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $lists)) {
			
			return __('List', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $maps)) {
			
			return __('Map', 'poptheme-wassup-sectionprocessors');
		}

		return parent::get_panel_header_title($blockgroup, $blockunit, $atts);
	}
	function get_panel_header_tooltip($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS:
				
				return $this->get_panel_header_title($blockgroup, $blockunit, $atts);
		}

		return parent::get_panel_header_tooltip($blockgroup, $blockunit);
	}

	function get_default_active_blockunit($template_id) {

		$pages_id = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS,
		);
		if ($page_id = $pages_id[$template_id]) {

			global $gd_template_settingsmanager;
			return $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
		}
	
		return parent::get_default_active_blockunit($template_id);
	}

	function init_atts($template_id, &$atts) {

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS,
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
new GD_Custom_Template_Processor_TagSectionTabPanelBlockGroups();

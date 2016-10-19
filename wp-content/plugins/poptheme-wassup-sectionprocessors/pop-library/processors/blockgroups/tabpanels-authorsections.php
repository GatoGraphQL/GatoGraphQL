<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-authorprojects'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-authorstories'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-authorannouncements'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-authordiscussions'));

class GD_Custom_Template_Processor_AuthorSectionTabPanelBlockGroups extends GD_Template_Processor_AuthorSectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		global $author;
		if (gd_ure_is_community($author)) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS:

					$ret[] = GD_URE_TEMPLATE_CONTROLGROUP_CONTENTSOURCE;
					break;
			}
		}
	
		return $ret;
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_LIST,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLLMAP => array(),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES:

				$ret = array(
					GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS:

				$ret = array(
					GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_LIST,
					),
				);
				break;
		}

		if ($ret) {
			return apply_filters('GD_Custom_Template_Processor_AuthorSectionTabPanelBlockGroups:panel_headers', $ret, $template_id);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS:

				return GD_TEMPLATE_FILTER_AUTHORPROJECTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES:
			
				return GD_TEMPLATE_FILTER_AUTHORSTORIES;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS:
			
				return GD_TEMPLATE_FILTER_AUTHORANNOUNCEMENTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS:
			
				return GD_TEMPLATE_FILTER_AUTHORDISCUSSIONS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLLMAP,
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
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORSTORIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLLMAP,
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

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS:
				
				return $this->get_panel_header_title($blockgroup, $blockunit, $atts);
		}

		return parent::get_panel_header_tooltip($blockgroup, $blockunit);
	}

	function get_default_active_blockunit($template_id) {

		$pages_id = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS,
		);
		if ($page_id = $pages_id[$template_id]) {

			global $gd_template_settingsmanager;
			return $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_AUTHOR);
		}
	
		return parent::get_default_active_blockunit($template_id);
	}

	function init_atts($template_id, &$atts) {

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS,
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
new GD_Custom_Template_Processor_AuthorSectionTabPanelBlockGroups();

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-authorfarms'));

class OP_Template_Processor_AuthorSectionTabPanelBlockGroups extends GD_Template_Processor_AuthorSectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		global $author;
		if (gd_ure_is_community($author)) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS:

					$ret[] = GD_URE_TEMPLATE_CONTROLGROUP_CONTENTSOURCE;
					break;
			}
		}
	
		return $ret;
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS:

				$ret = array(
					GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP => array(),
				);
				break;
		}

		if ($ret) {
			return apply_filters('OP_Template_Processor_AuthorSectionTabPanelBlockGroups:panel_headers', $ret, $template_id);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS:

				return GD_TEMPLATE_FILTER_AUTHORFARMS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
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
	function get_panel_header_title($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup-organikprocessors');
		}
		elseif (in_array($blockunit, $simpleviews)) {
			
			return __('Feed', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $fullviews)) {
			
			return __('Extended', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return __('Thumbnail', 'poptheme-wassup-organikprocessors');
		}
		elseif (in_array($blockunit, $lists)) {
			
			return __('List', 'poptheme-wassup-organikprocessors');
		}
		elseif (in_array($blockunit, $maps)) {
			
			return __('Map', 'poptheme-wassup-organikprocessors');
		}

		return parent::get_panel_header_title($blockgroup, $blockunit);
	}
	function get_panel_header_tooltip($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS:
				
				return $this->get_panel_header_title($blockgroup, $blockunit);
		}

		return parent::get_panel_header_tooltip($blockgroup, $blockunit);
	}

	function get_default_active_blockunit($template_id) {

		$pages_id = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS,
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
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS,
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
new OP_Template_Processor_AuthorSectionTabPanelBlockGroups();

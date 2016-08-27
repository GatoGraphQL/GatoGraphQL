<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS', PoP_ServerUtils::get_template_definition('blockgroup-farms-tabpanel'));

// My Content
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS', PoP_ServerUtils::get_template_definition('blockgroup-myfarms-tabpanel'));

class OP_Custom_Template_Processor_SectionTabPanelBlockGroups extends GD_Template_Processor_SectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS:

				// Only reload/destroy if these are main blocks
				if ($this->get_att($template_id, $atts, 'is-mainblock')) {
					$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
					$this->add_jsmethod($ret, 'refetchBlockGroupOnUserLoggedIn');
				}
				break;
		}

		return $ret;
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS:

				return array(
					GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP => array(),
					GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,
					),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS:

				return array(
					GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT => array(),
					GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW => array(
						GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
					),
				);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	protected function get_controlgroup_top($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS:

					return GD_TEMPLATE_CONTROLGROUP_POSTLIST;
				
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS:

					return GD_TEMPLATE_CONTROLGROUP_MYFARMLIST;
			}
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS:

				return GD_TEMPLATE_FILTER_FARMS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS:

				return GD_TEMPLATE_FILTER_MYFARMS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
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
		elseif (in_array($blockunit, $thumbnails)) {
			
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

		$details = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup-organikprocessors');
		}
		elseif (in_array($blockunit, $simpleviews) || in_array($blockunit, $simpleviewpreviews)) {
			
			return __('Feed', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $fullviews) || in_array($blockunit, $fullviewpreviews)) {
			
			return __('Extended', 'poptheme-wassup-sectionprocessors');
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
		elseif (in_array($blockunit, $edits)) {
			
			return __('Edit', 'poptheme-wassup-organikprocessors');
		}
		// elseif (in_array($blockunit, $fullviewpreviews)) {
			
		// 	return __('View/Preview', 'poptheme-wassup-organikprocessors');
		// }

		return parent::get_panel_header_title($blockgroup, $blockunit);
	}

	function init_atts($template_id, &$atts) {

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS,
		);
		$tables = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS,
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
new OP_Custom_Template_Processor_SectionTabPanelBlockGroups();

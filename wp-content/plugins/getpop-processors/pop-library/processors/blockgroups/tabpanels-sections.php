<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-websitefeatures-formats'));

class GetPoP_Template_Processor_SectionTabPanelBlockGroups extends GD_Template_Processor_DefaultActiveTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS,
		);
	}

	// function get_default_active_blockunit($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

	// 			return GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST;
	// 	}

	// 	return parent::get_default_active_blockunit($template_id);
	// }

	function is_active_blockunit($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

				switch ($blockunit) {

					case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST;
						
						return true;
				}
				break;
		}
	
		return parent::is_active_blockunit($blockgroup, $blockunit);
	}

	// function intercept($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

	// 			return false;
	// 	}

	// 	return parent::intercept($template_id);
	// }

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

				return __('Different formats to display results', 'getpop-processors');
		}

		return parent::get_title($template_id);
	}
	
	protected function get_description($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

				return sprintf(
					'<pre class="breakable">%s</pre>',
					__('Posts and users can be visualized in different formats. Eg: as a list or grid of items, full view into each, in a Google map, or you can create your own visualizations.', 'getpop-processors')
				);
		}

		return parent::get_description($template_id, $atts);
	}
	protected function get_title_htmltag($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

				return 'h2';
		}

		return parent::get_title_htmltag($template_id, $atts);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

				$ret[] = GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_DETAILS;
				$ret[] = GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL;
				$ret[] = GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST;
				$ret[] = GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_FULLVIEW;

				// Allow EM to add GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP
				$ret = apply_filters('GetPoP_Template_Processor_SectionTabPanelBlockGroups:blockgroup_blocks', $ret, $template_id);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

				return array(
					GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_DETAILS,
					),
					GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP => array(),
				);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	// protected function get_controlgroup_top($template_id) {

	// 	// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
	// 	$vars = GD_TemplateManager_Utils::get_vars();
	// 	if (!$vars['fetching-json-quickview']) {

	// 		switch ($template_id) {

	// 			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

	// 				return GD_TEMPLATE_CONTROLGROUP_POSTLIST;
	// 		}
	// 	}

	// 	return parent::get_controlgroup_top($template_id);
	// }

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_DETAILS,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP,
		);

		if (in_array($blockunit, $details)) {

			return 'fa-th-list';
		}
		elseif (in_array($blockunit, $fullviews)) {
			
			return 'fa-road';
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
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_DETAILS,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $fullviews)) {
			
			return __('Full view', 'poptheme-wassup-sectionprocessors');
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

		return parent::get_panel_header_title($blockgroup, $blockunit);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS:

				// Remove the title in all blocks
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP) {
					$this->add_att(GD_TEMPLATE_SCROLL_WHOWEARE_MAP, $blockgroup_block_atts, 'theatermap', false);
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_SectionTabPanelBlockGroups();

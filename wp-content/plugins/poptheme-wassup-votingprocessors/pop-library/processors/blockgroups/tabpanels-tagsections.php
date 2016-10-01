<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagopinionatedvotes'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagopinionatedvotes-pro'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagopinionatedvotes-neutral'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-tagopinionatedvotes-against'));

class VotingProcessors_Template_Processor_TagSectionTabPanelBlockGroups extends GD_Template_Processor_TagSectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_FULLVIEW,
						// GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_THUMBNAIL,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_FULLVIEW,
						// GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_THUMBNAIL,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_FULLVIEW,
						// GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_THUMBNAIL,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_FULLVIEW,
						// GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_THUMBNAIL,
					)
				);
				break;
		}

		$ret = apply_filters('VotingProcessors_Template_Processor_TagSectionTabPanelBlockGroups:blocks', $ret, $template_id);

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_THUMBNAIL,
					),
					// GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_DETAILS => array(
					// 	GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_DETAILS,
					// 	GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_THUMBNAIL,
					// 	GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_LIST,
					// ),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_THUMBNAIL,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_THUMBNAIL,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_THUMBNAIL,
					),
				);
				break;
		}

		if ($ret) {
			return apply_filters('VotingProcessors_Template_Processor_TagSectionTabPanelBlockGroups:panel_headers', $ret, $template_id);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES:
				
				return GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST:
				
				return GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES_STANCE;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		// $details = array(
		// 	GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_DETAILS,
		// );
		$fullviews = array(
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_FULLVIEW,
		);
		$grids = array(
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_LIST,
		);

		/*if (in_array($blockunit, $details)) {

			return 'fa-th-list';
		}
		else*/if (in_array($blockunit, $fullviews)) {
			
			return 'fa-road';
		}
		elseif (in_array($blockunit, $grids)) {
			
			return 'fa-th';
		}
		elseif (in_array($blockunit, $lists)) {
			
			return 'fa-list';
		}

		return parent::get_panel_header_fontawesome($blockgroup, $blockunit);
	}
	function get_panel_header_title($blockgroup, $blockunit) {

		// $details = array(
		// 	GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_DETAILS,
		// );
		$fullviews = array(
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_FULLVIEW,
		);
		$grids = array(
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_PRO_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_NEUTRAL_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGOPINIONATEDVOTES_AGAINST_SCROLL_LIST,
		);

		/*if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup-votingprocessors');
		}
		else*/if (in_array($blockunit, $fullviews)) {
			
			return __('Full view', 'poptheme-wassup-votingprocessors');
		}
		elseif (in_array($blockunit, $grids)) {
			
			return __('Grid', 'poptheme-wassup-votingprocessors');
		}
		elseif (in_array($blockunit, $lists)) {
			
			return __('List', 'poptheme-wassup-votingprocessors');
		}

		return parent::get_panel_header_title($blockgroup, $blockunit);
	}
	function get_panel_header_tooltip($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST:
				
				return $this->get_panel_header_title($blockgroup, $blockunit);
		}

		return parent::get_panel_header_tooltip($blockgroup, $blockunit);
	}

	function get_default_active_blockunit($template_id) {

		$pages_id = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST,
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
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST,
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
new VotingProcessors_Template_Processor_TagSectionTabPanelBlockGroups();

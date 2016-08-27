<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES', PoP_ServerUtils::get_template_definition('blockgroup-communities-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('blockgroup-organizations-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS', PoP_ServerUtils::get_template_definition('blockgroup-individuals-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS', PoP_ServerUtils::get_template_definition('blockgroup-mymembers-tabpanel'));

class GD_URE_Template_Processor_SectionTabPanelBlockGroups extends GD_Template_Processor_SectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS,

			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS:

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

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES:

				return array(
					GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP => array(),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS:

				return array(
					GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP => array(),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS:

				return array(
					GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW => array(),
					GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS => array(
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP => array(),
				);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	protected function get_controlgroup_top($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {
				
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS:

					return GD_TEMPLATE_CONTROLGROUP_USERLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS:
	
					return GD_TEMPLATE_CONTROLGROUP_MYMEMBERS;
			}
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS:

				return GD_TEMPLATE_FILTER_ORGANIZATIONS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS:

				return GD_TEMPLATE_FILTER_INDIVIDUALS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS:

				return GD_TEMPLATE_FILTER_MYMEMBERS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,
			
			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
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
		elseif (in_array($blockunit, $edits)) {
			
			return 'fa-edit';
		}

		return parent::get_panel_header_fontawesome($blockgroup, $blockunit);
	}
	function get_panel_header_title($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,

			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $fullviews)) {
			
			return __('Full view', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return __('Thumbnail', 'poptheme-wassup');
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

		return parent::get_panel_header_title($blockgroup, $blockunit);
	}

	function init_atts($template_id, &$atts) {

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS,
		);
		$tables = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS,
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
new GD_URE_Template_Processor_SectionTabPanelBlockGroups();

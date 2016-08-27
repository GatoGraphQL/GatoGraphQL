<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Side Tab Panels
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR', PoP_ServerUtils::get_template_definition('blockgroup-tabpanel-navigator'));

/**
 * Comment Leo 03/11/2015: class and template are Deprecated
 */
class GD_Template_Processor_SideTabPanelBlockGroups extends GD_Template_Processor_DefaultActiveTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		$theme = GD_TemplateManager_Utils::get_theme();
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

				$ret = array_merge(
					$ret,
					apply_filters(
						'GD_Template_Processor_SideTabPanelBlockGroups:navigator:blocks',
						array(
							GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_NAVIGATOR,
							GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_NAVIGATOR,
						)
					)
				);
				break;
		}

		return $ret;
	}

	function intercept($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

				return true;
		}

		return parent::intercept($template_id);
	}

	function get_intercept_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

				return GD_INTERCEPT_TARGET_NAVIGATOR;
		}

		return parent::get_intercept_target($template_id, $atts);
	}

	function get_intercept_skipstateupdate($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

				return true;
		}

		return parent::get_intercept_skipstateupdate($template_id, $atts);
	}

	function get_panel_header_type($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

				return null;
		}

		return parent::get_panel_header_type($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

				// Whenever clicking a link inside of the tabPanel, the interceptor must be found for the Navigator Container
				$this->merge_att($template_id, $atts, 'params', array(
					'data-intercept-target' => GD_INTERCEPT_TARGET_NAVIGATOR
				));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		global $gd_template_settingsmanager;

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SideTabPanelBlockGroups();

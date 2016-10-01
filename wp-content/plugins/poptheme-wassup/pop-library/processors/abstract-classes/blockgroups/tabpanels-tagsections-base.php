<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TagSectionTabPanelBlockGroupsBase extends GD_Template_Processor_GenericSectionTabPanelBlockGroupsBase {

	protected function get_controlgroup_bottom($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			return GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLIST;
		}

		return parent::get_controlgroup_bottom($template_id);
	}

	function get_title($template_id) {

		return GD_Template_Processor_CustomSectionBlocksUtils::get_tag_title();
	}

	function get_submenu($template_id) {

		// Do not add for the quickview
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {
		
			return GD_TEMPLATE_SUBMENU_TAG;
		}
		
		return parent::get_submenu($template_id);
	}

	function is_active_blockunit($blockgroup, $blockunit) {

		global $gd_template_settingsmanager;
		if ($page_id = GD_TemplateManager_Utils::get_hierarchy_page_id()) {
			return ($gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG) == $blockunit);
		}
	
		return parent::is_active_blockunit($blockgroup, $blockunit);
	}

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'blockgroup-tagsections');
		
		// Hide the tab title
		$this->append_att($template_id, $atts, 'class', 'pop-tabtitle-hidden');

		return parent::init_atts($template_id, $atts);
	}
}
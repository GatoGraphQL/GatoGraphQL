<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SectionTabPanelBlockGroupsBase extends GD_Template_Processor_GenericSectionTabPanelBlockGroupsBase {

	function get_default_active_blockunit($template_id) {

		global $gd_template_settingsmanager;
		$page_id = $gd_template_settingsmanager->get_blockgroup_page($template_id);
		return $gd_template_settingsmanager->get_page_block($page_id);
	}

	function get_title($template_id) {

		global $gd_template_settingsmanager;
		if ($page_id = $gd_template_settingsmanager->get_blockgroup_page($template_id)) {

			return get_the_title($page_id);
		}

		return parent::get_title($template_id);
	}

	function is_active_blockunit($blockgroup, $blockunit) {

		global $gd_template_settingsmanager;
		return ($gd_template_settingsmanager->get_page_block() == $blockunit);
	}
}
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SectionTabPanelBlockGroupsBase extends GD_Template_Processor_DefaultActiveTabPanelBlockGroupsBase {

	function get_default_active_blockunit($template_id) {

		global $gd_template_settingsmanager;
		$page_id = $gd_template_settingsmanager->get_blockgroup_page($template_id);
		return $gd_template_settingsmanager->get_page_block($page_id);
	}

	function intercept($template_id) {

		return true;
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

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		global $gd_template_settingsmanager;

		// Hide the Title, Controls, Filter
		$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');		
		$this->add_att($blockgroup_block, $blockgroup_block_atts, 'filter-hidden', true);	

		$this->add_att($blockgroup_block, $blockgroup_block_atts, 'show-controls', false);		

		// Set lazy for the blocks: Do not load the content if not showing it initially
		// This doesn't work for Search, since its content is loaded (even though it has no content)
		$is_active_blockunit = $this->is_active_blockunit($blockgroup, $blockgroup_block);
		if (!$is_active_blockunit) {

			$this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);		
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}
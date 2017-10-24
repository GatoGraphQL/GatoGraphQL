<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class PoP_Processor_BlockGroupsBase extends PoP_Processor_BlocksBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_blockgroup_blocks($template_id) {

		return array();
	}
	function get_blockgroup_blockgroups($template_id) {

		return array();
	}
	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		return $blockgroup_block_atts;
	}
	function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

		return $blockgroup_blockgroup_atts;
	}

	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	protected function get_block_page($template_id) {

		global $gd_template_settingsmanager;

		if ($page = $gd_template_settingsmanager->get_blockgroup_page($template_id, $this->get_block_hierarchy($template_id))) {

			return $page;
		}
		return null;
	}

	function is_blockgroup($template_id) {

		return true;
	}

	function get_dataload_source($template_id, $atts) {

		// The BlockGroup doesn't have a dataload-source of its own, it's just used as a container to other blocks
		return null;
	}

	function get_extra_blocks($template_id) {

		global $gd_template_processor_manager;
	
		$ret = parent::get_extra_blocks($template_id);

		$blocks = $this->get_blockgroup_blocks($template_id);
		$this->add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_INDEPENDENT);

		$blockgroup_blockgroups = $this->get_blockgroup_blockgroups($template_id);
		foreach ($blockgroup_blockgroups as $blockgroup_blockgroup) {
			$blockgroup_blockgroup_extra_blocks = $gd_template_processor_manager->get_processor($blockgroup_blockgroup)->get_extra_blocks($blockgroup_blockgroup);
			$ret = array_unique(
				array_merge_recursive(
					$ret,
					$blockgroup_blockgroup_extra_blocks
				)
			);
		}

		return $ret;
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	protected function get_blockgroup_blockunits($template_id) {

		return array_merge(
			$this->get_blockgroup_blocks($template_id),
			$this->get_blockgroup_blockgroups($template_id)
		);
	}
}

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class PoPFrontend_Processor_BlockGroupsBase extends PoPFrontend_Processor_BlocksBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_blockgroup_blocks($template_id) {

		return array();
	}
	
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_blockgroup_blockgroups($template_id) {

		return array();
	}
	
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		return $blockgroup_block_atts;
	}
	
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

		return $blockgroup_blockgroup_atts;
	}

	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_block_page($template_id) {

		global $gd_template_settingsmanager;

		if ($page = $gd_template_settingsmanager->get_blockgroup_page($template_id)) {

			return $page;
		}
		return null;
	}

	function get_title($template_id) {

		// By default, do not make the blockgroup take the title from its blockgroup-page
		return null;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function is_blockgroup($template_id) {

		return true;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_dataload_source($template_id, $atts) {

		// The BlockGroup doesn't have a dataload-source of its own, it's just used as a container to other blocks
		return null;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
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

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// Add the settings-ids of all blocks
		if (!$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]) {
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/] = array();
		}

		// $blockunits = $this->get_blockgroup_blockunits($template_id);
		$blockunits = $this->get_ordered_blockgroup_blockunits($template_id);
		$blockunits_settings_ids = array();
		foreach ($blockunits as $blockunit) {
			
			$blockunits_settings_ids[] = $gd_template_processor_manager->get_processor($blockunit)->get_settings_id($blockunit);
		}
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][GD_JS_BLOCKUNITS/*'blockunits'*/] = $blockunits_settings_ids;
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Change the base class from pop-block to pop-blockgroup
		$this->append_att($template_id, $atts, 'class', 'pop-blockgroup');		
		
		// Initialize JS leaves and branches
		$this->merge_att($template_id, $atts, 'initjs-blockbranches', $this->get_initjs_blockbranches($template_id, $atts));
		$this->merge_att($template_id, $atts, 'initjs-blockchildren', $this->get_initjs_blockchildren($template_id, $atts));

		// Comment Leo 07/07/2016: Commented below since allowing the BlockGroup to override att 'form-type' in the filter, with value GD_SUBMITFORMTYPE_FILTERBLOCKGROUP
		// $this->add_att($template_id, $atts, 'filter-jsmethod', 'initBlockGroupFilter');
		if ($filter = $this->get_filter_template($template_id)) {
			if ($show_filter = $this->get_att($template_id, $atts, 'show-filter')) {
				$this->add_att($filter, $atts, 'form-type', GD_SUBMITFORMTYPE_FILTERBLOCKGROUP);
			}
		}

		return parent::init_atts($template_id, $atts);
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------
	
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_blockgroup_blockunits($template_id) {

		return array_merge(
			$this->get_blockgroup_blocks($template_id),
			$this->get_blockgroup_blockgroups($template_id)
		);
	}

	protected function show_status($template_id) {

		return false;
	}

	protected function get_initjs_blockbranches($template_id, $atts) {

		return array();
	}
	protected function get_initjs_blockchildren($template_id, $atts) {

		return array();
	}

	protected function get_ordered_blockgroup_blockunits($template_id) {
	
		return $this->get_blockgroup_blockunits($template_id);
	}
}

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Processor_BlockGroupsBaseWrapper extends PoP_Processor_BlocksBaseWrapper {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_blockgroup_blocks($template_id) {

		return $this->processor->get_blockgroup_blocks($template_id);
	}
	function get_blockgroup_blockgroups($template_id) {

		return $this->processor->get_blockgroup_blockgroups($template_id);
	}
	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		return $this->processor->init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
	function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

		return $this->processor->init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, $blockgroup_blockgroup_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('PoP_Processor_BlockGroupsBase', 'PoP_Processor_BlockGroupsBaseWrapper');
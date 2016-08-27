<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Processor_BlockGroupsBaseWrapper extends PoPFrontend_Processor_BlocksBaseWrapper {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_blockgroup_blocks($template_id) {

		return $this->processor->get_blockgroup_blocks($template_id);
	}
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_blockgroup_blockgroups($template_id) {

		return $this->processor->get_blockgroup_blockgroups($template_id);
	}
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		return $this->processor->init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

		return $this->processor->init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, $blockgroup_blockgroup_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('PoPFrontend_Processor_BlockGroupsBase', 'PoPFrontend_Processor_BlockGroupsBaseWrapper');
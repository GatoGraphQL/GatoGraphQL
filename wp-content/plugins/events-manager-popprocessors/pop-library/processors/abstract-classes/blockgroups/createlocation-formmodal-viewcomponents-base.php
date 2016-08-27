<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateLocationFormModalViewComponentBlockGroupsBase extends GD_Template_Processor_FormModalViewComponentBlockGroupsBase {

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);
		$ret[] = GD_TEMPLATE_BLOCK_CREATELOCATION;
		$ret[] = GD_TEMPLATE_BLOCKDATA_CREATELOCATION;
		return $ret;
	}

	function get_dialog_class($template_id) {

		$ret = parent::get_dialog_class($template_id);
		$ret .= ' modal-lg';
		return $ret;
	}
	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'createLocationModal', $this->get_type($template_id));
		return $ret;
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		if ($blockgroup_block == GD_TEMPLATE_BLOCK_CREATELOCATION) {
			
			// Hide the Title
			$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');		
			
			// Make the block close the modal when the execution was successful
			$this->merge_block_jsmethod_att($blockgroup_block, $blockgroup_block_atts, array('maybeCloseLocationModal'));
		}
		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}
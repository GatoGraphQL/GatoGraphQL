<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ModalViewComponentBlockGroupsBase extends GD_Template_Processor_ViewComponentBlockGroupsBase {

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'modal', $this->get_type($template_id));
		return $ret;
	}

	function get_type($template_id) {

		return 'modal';
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		// Do not initialize JS immediately, it shall be done when the modal is shown
		$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', GD_CLASS_LAZYJS);		
		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
	function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

		// Do not initialize JS immediately, it shall be done when the modal is shown
		$this->append_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'class', GD_CLASS_LAZYJS);		
		return parent::init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, $blockgroup_blockgroup_atts, $blockgroup_atts);
	}
}
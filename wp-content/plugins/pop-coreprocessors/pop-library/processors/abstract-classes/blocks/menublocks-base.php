<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MenuBlocksBase extends GD_Template_Processor_BlocksBase {

	function get_menu($template_id) {

		return null;
	}

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);

		$ret['menu'] = $this->get_menu($template_id);

		return $ret;
	}

	function get_dataloader($template_id) {

		return GD_DATALOADER_MENU;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);	

		// Add "active" to current links in corresponding links
		$this->add_jsmethod($ret, 'activeLinks');		
		
		return $ret;
	}
}
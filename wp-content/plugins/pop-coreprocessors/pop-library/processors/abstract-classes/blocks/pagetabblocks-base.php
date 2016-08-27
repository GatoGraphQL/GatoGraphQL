<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TabBlocksBase extends GD_Template_Processor_BareBlocksBase {

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);
		$ret[] = GD_TEMPLATE_LAYOUT_PAGETAB;
		return $ret;
	}

	protected function show_status($template_id) {

		return false;
	}

	protected function show_disabled_layer($template_id) {

		return false;
	}
}
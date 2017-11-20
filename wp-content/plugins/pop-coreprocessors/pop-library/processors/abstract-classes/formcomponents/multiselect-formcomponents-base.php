<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MultiSelectFormComponentsBase extends GD_Template_Processor_SelectFormComponentsBase {

	function is_multiple($template_id, $atts) {

		return true;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		// Make it critical because the multiselect looks very ugly without initializing
		$this->add_jsmethod($ret, 'multiselect', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	$this->append_att($template_id, $atts, 'class', 'make-multiselect');
	// 	return parent::init_atts($template_id, $atts);
	// }
}

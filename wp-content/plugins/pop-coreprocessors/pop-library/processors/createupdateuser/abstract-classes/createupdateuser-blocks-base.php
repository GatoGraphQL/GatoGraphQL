<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateUpdateUserBlocksBase extends GD_Template_Processor_BlocksBase {

	function init_atts($template_id, &$atts) {

		$submitting = __('Submitting...', 'pop-coreprocessors');
		$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', $submitting);
		
		return parent::init_atts($template_id, $atts);
	}

	protected function get_iohandler($template_id) {

		return GD_DATALOAD_IOHANDLER_FORM;
	}
}
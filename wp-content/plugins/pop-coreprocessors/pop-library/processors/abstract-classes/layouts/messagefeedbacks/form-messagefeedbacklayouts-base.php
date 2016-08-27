<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormMessageFeedbackLayoutsBase extends GD_Template_Processor_MessageFeedbackLayoutsBase {

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);
		$ret['error-header'] = __('Ops, there were some problems:', 'pop-coreprocessors');
		$ret['success-header'] = __('Success!', 'pop-coreprocessors');

		return $ret;
	}
}
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ListMessageFeedbackLayoutsBase extends GD_Template_Processor_MessageFeedbackLayoutsBase {

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);
		$ret['noresults'] = __('No results', 'pop-coreprocessors');
		$ret['nomore'] = __('No more results found', 'pop-coreprocessors');

		return $ret;
	}
}
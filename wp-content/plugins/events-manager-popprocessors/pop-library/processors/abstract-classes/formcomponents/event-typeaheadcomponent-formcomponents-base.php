<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_EventTypeaheadComponentFormComponentsBase extends GD_Template_Processor_PostTypeaheadComponentFormComponentsBase {

	protected function get_thumbprint_query($template_id, $atts) {

		$ret = parent::get_thumbprint_query($template_id, $atts);

		$ret['post_type'] = EM_POST_TYPE_EVENT;
		
		return $ret;
	}

	protected function get_pending_msg($template_id) {

		return __('Loading Events', 'em-popprocessors');
	}
	protected function get_notfound_msg($template_id) {

		return __('No Events found', 'em-popprocessors');
	}
}

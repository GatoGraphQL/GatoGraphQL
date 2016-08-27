<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserQuickLinkLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTUSER_QUICKLINKS;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('contact-small');
	}

	function get_nocontact_title($template_id, $atts) {
	
		return __('No contact channels', 'pop-coreprocessors');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_TITLES/*'titles'*/]['nocontact'] = $this->get_nocontact_title($template_id, $atts);
		
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		$this->add_jsmethod($ret, 'doNothing', 'void-link');

		return $ret;
	}	
}
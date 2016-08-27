<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_StatusBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_STATUS;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);
		
		$loading = sprintf(
			'%s %s', 
			$this->get_att($template_id, $atts, 'loading-spinner'),
			$this->get_att($template_id, $atts, 'loading-msg')
		);
		$ret[GD_JS_TITLES/*'titles'*/]['loading'] = $loading;
		$ret[GD_JS_TITLES/*'titles'*/]['error'] = GD_CONSTANT_ERROR_MSG;
		$ret[GD_JS_TITLES/*'titles'*/]['retry'] = GD_CONSTANT_RETRY_MSG;

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		$this->add_jsmethod($ret, 'switchTargetClass', 'error-dismiss');
		$this->add_jsmethod($ret, 'retrySendRequest', 'retry');

		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		$this->add_att($template_id, $atts, 'class', 'top');
		$this->add_att($template_id, $atts, 'loading-msg', GD_CONSTANT_LOADING_MSG);
		$this->add_att($template_id, $atts, 'loading-spinner', GD_CONSTANT_LOADING_SPINNER);
		
		return parent::init_atts($template_id, $atts);
	}
}
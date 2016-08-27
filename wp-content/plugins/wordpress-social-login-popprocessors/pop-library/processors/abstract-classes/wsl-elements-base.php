<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_WSLElementsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_WSL_NETWORKLINKS;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'wslNetworkLink', 'links');
		return $ret;
	}

	function get_btn_class($template_id, $atts) {

		return 'btn btn-default';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		$ret['networklinks'] = gd_wsl_networklinks();
		$ret[GD_JS_CLASSES/*'classes'*/]['link'] = $this->get_btn_class($template_id, $atts);
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		$this->append_att($template_id, $atts, 'class', 'wsl-networklinks');
		
		return parent::init_atts($template_id, $atts);
	}

}

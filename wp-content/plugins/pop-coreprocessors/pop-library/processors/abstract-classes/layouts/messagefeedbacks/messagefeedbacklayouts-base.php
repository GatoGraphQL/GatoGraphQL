<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MessageFeedbackLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_MESSAGEFEEDBACK;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);			
		
		$ret['messages'] = $this->get_messages($template_id, $atts);
		
		return $ret;
	}	

	function get_messages($template_id, $atts) {
	
		return array();
	}

	// function init_atts($template_id, &$atts) {

	// 	$this->append_att($template_id, $atts, 'class', 'overflow-visible');			
	// 	return parent::init_atts($template_id, $atts);
	// }
}
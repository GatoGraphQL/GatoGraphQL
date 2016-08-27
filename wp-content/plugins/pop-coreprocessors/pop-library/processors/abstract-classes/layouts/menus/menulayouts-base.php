<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MenuLayoutsBase extends GD_Template_ProcessorBase {

	function get_data_fields($template_id, $atts) {
	
		return array('id', 'items');
	}

	function get_item_class($template_id, $atts) {
	
		return '';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_CLASSES/*'classes'*/]['item'] = $this->get_item_class($template_id, $atts);
		
		return $ret;
	}
}
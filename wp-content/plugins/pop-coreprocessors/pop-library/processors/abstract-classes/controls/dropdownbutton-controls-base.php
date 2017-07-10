<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DropdownButtonControlsBase extends GD_Template_Processor_ControlsBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CONTROL_DROPDOWNBUTTON;
	}
	function get_btn_class($template_id) {
		
		return 'btn btn-default';
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		if ($class = $this->get_btn_class($template_id)) {
			// $ret['btn-class'] = $class;
			$ret[GD_JS_CLASSES/*'classes'*/]['btn'] = $class;
		}
		
		return $ret;
	}
}
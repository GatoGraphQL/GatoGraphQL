<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ButtonControlsBase extends GD_Template_Processor_ControlsBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CONTROL_BUTTON;
	}

	function get_type($template_id) {

		return 'button';
	}
	function get_btn_class($template_id, $atts) {
		
		return 'btn btn-default';
	}
	function get_text_class($template_id) {
		
		return 'hidden-xs';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($type = $this->get_type($template_id)) {
			$ret['type'] = $type;
		}
		if ($text_class = $this->get_text_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['text'] = $text_class;
		}

		return $ret;
	}
	
	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', $this->get_btn_class($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}
}
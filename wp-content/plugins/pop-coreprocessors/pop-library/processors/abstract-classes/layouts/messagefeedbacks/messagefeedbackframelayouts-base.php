<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MessageFeedbackFrameLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_MESSAGEFEEDBACKFRAME;
	}
	
	function get_layout($template_id) {

		return null;
	}	
	
	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
		$ret[] = $this->get_layout($template_id);
		return $ret;
	}	

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$layout = $this->get_layout($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout'] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
		
		return $ret;
	}
}
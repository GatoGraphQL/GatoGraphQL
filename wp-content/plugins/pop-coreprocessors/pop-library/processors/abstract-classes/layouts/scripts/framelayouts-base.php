<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ScriptFrameLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_SCRIPTFRAME;
	}

	function get_layout_template($template_id) {
	
		return null;
	}

	function get_script_template($template_id) {
	
		return null;
	}

	function get_modules($template_id) {

		return array_merge(
			parent::get_modules($template_id),
			array(
				$this->get_layout_template($template_id),
				$this->get_script_template($template_id),
			)
		);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
		
		$layout = $this->get_layout_template($template_id);
		$script = $this->get_script_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout'] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['script'] = $gd_template_processor_manager->get_processor($script)->get_settings_id($script);
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		$script = $this->get_script_template($template_id);
		$this->add_att($script, $atts, 'frame-template', $template_id);
		return parent::init_atts($template_id, $atts);
	}
}
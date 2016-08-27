<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_InputGroupFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_INPUTGROUP;
	}

	function get_controls($template_id) {

		return array();
	}
	function get_input_template($template_id) {

		return null;
	}
	function get_inputgroupbtn_class($template_id) {

		return '';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret[GD_JS_CLASSES/*'classes'*/]['input-group-btn'] = $this->get_inputgroupbtn_class($template_id);

		$counter = 0;
		$keys = array();
		foreach ($this->get_controls($template_id) as $control) {
			$key = 'a'.$counter++;
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$key] = $gd_template_processor_manager->get_processor($control)->get_settings_id($control);
			$keys[] = $key;
		}
		$ret['settings-keys']['controls'] = $keys;

		if ($input = $this->get_input_template($template_id)) {
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['input'] = $gd_template_processor_manager->get_processor($input)->get_settings_id($input);
		}
		return $ret;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		if ($input = $this->get_input_template($template_id)) {
			$ret[] = $input;
		}

		$ret = array_merge(
			$ret,
			$this->get_controls($template_id)
		);

		return $ret;
	}
}

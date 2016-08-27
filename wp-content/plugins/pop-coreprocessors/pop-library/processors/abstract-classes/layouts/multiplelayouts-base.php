<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MultipleLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_MULTIPLE;
	}

	function get_default_layout($template_id) {

		return null;
	}

	function get_multiple_layouts($template_id) {

		return array();
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		$ret = array_merge(
			$ret,
			$this->get_multiple_layouts($template_id)
		);
		if ($default = $this->get_default_layout($template_id)) {
			if (!in_array($default, $this->get_multiple_layouts($template_id))) {
				$ret[] = $default;
			}
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		$this->append_att($template_id, $atts, 'class', 'pop-multilayout');

		return parent::init_atts($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$multiple_layouts = $this->get_multiple_layouts($template_id);
		$ret['multiple-layouts'] = $multiple_layouts;

		if ($default = $this->get_default_layout($template_id)) {
			$ret['multiple-layouts']['default'] = $default;
			$layouts['default'] = $gd_template_processor_manager->get_processor($default)->get_settings_id($default);
		}		
		foreach ($multiple_layouts as $key => $layout) {

			$layouts[$key] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
		}

		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['multiple-layouts'] = $layouts;

		return $ret;
	}
}
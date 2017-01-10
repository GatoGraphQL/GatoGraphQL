<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ConditionWrapperBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_CONDITIONWRAPPER;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		if ($layouts = $this->get_layouts($template_id)) {

			$ret = array_merge(
				$ret,
				$layouts
			);
		}

		if ($conditionfailed_layouts = $this->get_conditionfailed_layouts($template_id)) {

			$ret = array_merge(
				$ret,
				$conditionfailed_layouts
			);
		}

		return $ret;
	}

	function show_div($template_id, $atts) {
	
		return true;
	}

	function get_layouts($template_id) {
	
		return array();
	}

	function get_conditionfailed_layouts($template_id) {
	
		return array();
	}

	function get_data_fields($template_id, $atts) {

		return array(
			$this->get_condition_field($template_id),
		);
	}
	
	function get_condition_field($template_id) {
		
		// By returning 'id', the condition will always be true by default since all objects have an id >= 1
		return 'id';
	}

	function get_condition_method($template_id) {
		
		// Allow to execute a JS function on the object field value
		return null;
	}

	function get_conditionsucceeded_class($template_id, $atts) {
		
		return '';
	}

	function get_conditionfailed_class($template_id, $atts) {
		
		return '';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		if ($this->show_div($template_id, $atts)) {
			$ret['show-div'] = true;
		}
	
		if ($condition_field = $this->get_condition_field($template_id)) {
			$ret['condition-field'] = $condition_field;
		}
		if ($condition_method = $this->get_condition_method($template_id)) {
			$ret['condition-method'] = $condition_method;
		}
		if ($classs = $this->get_conditionsucceeded_class($template_id, $atts)) {
			$ret[GD_JS_CLASSES]['succeeded'] = $classs;
		}
		if ($classs = $this->get_conditionfailed_class($template_id, $atts)) {
			$ret[GD_JS_CLASSES]['failed'] = $classs;
		}

		if ($layouts = $this->get_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['layouts'] = $layouts;
			foreach ($layouts as $layout) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$layout] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
			}
		}

		if ($conditionfailed_layouts = $this->get_conditionfailed_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['conditionfailed-layouts'] = $conditionfailed_layouts;
			foreach ($conditionfailed_layouts as $layout) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$layout] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
			}
		}
		
		return $ret;
	}
}
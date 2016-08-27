<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormGroupsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMGROUP;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);	

		$this->add_jsmethod($ret, 'tooltip', 'tooltip');		

		return $ret;
	}

	function get_label($template_id, $atts) {

		return '';
	}

	function get_component($template_id) {

		return null;
	}

	function get_label_class($template_id) {

		return 'control-label';
	}
	function get_formcontrol_class($template_id) {

		return '';
	}
	function get_info($template_id, $atts) {

		return '';
	}
	function get_description($template_id, $atts) {

		return '';
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
		$ret[] = $this->get_component($template_id);
		return $ret;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		$component = $this->get_component($template_id);
		$component_processor = $gd_template_processor_manager->get_processor($component);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['component'] = $component_processor->get_settings_id($component);

		// Re-use the label from the component
		if ($label = $this->get_att($template_id, $atts, 'label')) {
			$ret['label'] = $label;
		}
		if ($label_class = $this->get_label_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['label'] = $label_class;
		}
		if ($formcontrol_class = $this->get_formcontrol_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['formcontrol'] = $formcontrol_class;
		}
		if ($info = $this->get_info($template_id, $atts)) {
			$ret['info'] = $info;
		}
		if ($description = $this->get_description($template_id, $atts)) {
			$ret[GD_JS_DESCRIPTION/*'description'*/] = $description;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// No need for the input to have a label or a placeholder (for the text inputs) anymore
		$label = $this->get_label($template_id, $atts);
		$this->add_att($template_id, $atts, 'label', $label);

		$this->append_att($template_id, $atts, 'class', 'form-group');
		return parent::init_atts($template_id, $atts);
	}
}

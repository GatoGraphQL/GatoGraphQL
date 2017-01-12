<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TypeaheadFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_components($template_id) {

		return array();
	}
	function get_input_template($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEAD;
	}

	function get_label($template_id, $atts) {

		global $gd_template_processor_manager;
		$input = $this->get_input_template($template_id);	
		return $gd_template_processor_manager->get_processor($input)->get_label($input, $atts);	
	}

	function init_atts($template_id, &$atts) {

		// Make the input hidden? This is needed when using the typeahead to pre-select a value,
		// but we don't want the user to keep selecting more. Eg: Add Highlight
		$input = $this->get_input_template($template_id);
		$this->append_att($input, $atts, 'class', 'typeahead');

		// Transfer properties down the line
		$label = $this->get_att($template_id, $atts, 'label');
		if (!is_null($label)) {
			$this->add_att($input, $atts, 'label', $label);
		}
		$placeholder = $this->get_att($template_id, $atts, 'placeholder');
		if (!is_null($placeholder)) {
			$this->add_att($input, $atts, 'placeholder', $placeholder);
		}

		return parent::init_atts($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret['typeahead-class'] = $this->get_typeahead_class($template_id, $atts);
		
		$input = $this->get_input_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['input'] = $gd_template_processor_manager->get_processor($input)->get_settings_id($input);

		return $ret;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		return array_merge(
			$ret,
			$this->get_components($template_id),
			array(
				$this->get_input_template($template_id)
			)
		);
	}
	function get_typeahead_class($template_id, $atts) {
	
		return 'pop-typeahead';
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		global $gd_template_processor_manager;

		// Comment Leo 12/02/2015: commented since switching from IDs to templateIds as the key to get the jsSetting
		// Save the ids of all components
		// $component_ids = array();
		// $components = $this->get_components($template_id);
		// foreach ($components as $component) {
		
		// 	$component_ids[] = $gd_template_processor_manager->get_processor($component)->get_id($component, $atts);
		// }

		// $ret['dataset-components'] = $component_ids;
		$ret['dataset-components'] = $this->get_components($template_id);

		return $ret;
	}

	function propagate_data_settings_components($mode, &$ret, $template_id, $atts) {
	
		// Important: the TYPEAHEAD_COMPONENT (eg: GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
		// but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole get_data_settings
		// To fix this, in GD_TEMPLATE_FORMCOMPONENT_TYPEAHEAD data_settings we stop spreading down, so it never reaches below there to get further data-fields
		
		// Do nothing 
		return $ret;
	}
}

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormComponentGroupsBase extends GD_Template_Processor_FormGroupsBase {

	function get_label($template_id, $atts) {

		if ($this->get_att($template_id, $atts, 'use-component-configuration')) {

			// If the input is hidden, then make the formgroup hidden too
			global $gd_template_processor_manager;
			$component = $this->get_component($template_id);
			$component_processor = $gd_template_processor_manager->get_processor($component);
			
			// No need for the input to have a label or a placeholder (for the text inputs) anymore
			return $component_processor->get_label($component, $atts);
		}

		return '';
	}

	function init_atts($template_id, &$atts) {

		// If the input is hidden, then make the formgroup hidden too
		global $gd_template_processor_manager;
		$component = $this->get_component($template_id);
		$component_processor = $gd_template_processor_manager->get_processor($component);
		if ($component_processor->is_hidden($component, $atts)) {

			$this->append_att($template_id, $atts, 'class', 'hidden');
		}

		// Collapse the inputgroup if the component is not mandatory. Eg: registration form: "Show/hide extra fields"
		if ($this->get_att($template_id, $atts, 'collapse-optionalfield')) {
			// if (!$component_processor->is_mandatory($component, $atts)) {
			if ($component_processor->collapsible($component, $atts)) {

				// Also add class "pop-highlight" so there is some flashing on the extra fields for the user to see
				$this->append_att($template_id, $atts, 'class', 'collapse pop-highlight');
			}
		}
		
		$this->add_att($template_id, $atts, 'use-component-configuration', $this->use_component_configuration($template_id));
		if ($this->get_att($template_id, $atts, 'use-component-configuration')) {

			// No need for the input to have a label or a placeholder (for the text inputs) anymore
			$label = $this->get_label($template_id, $atts);
			$this->add_att($template_id, $atts, 'label', $label);
			$this->add_att($component, $atts, 'label', '');
			$this->add_att($component, $atts, 'placeholder', '');
		}

		// $this->append_att($template_id, $atts, 'class', 'form-group');
		return parent::init_atts($template_id, $atts);
	}
	function use_component_configuration($template_id) {

		return true;
	}
}

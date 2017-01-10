<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ShowIfNotEmptyConditionWrapperBase extends GD_Template_Processor_ConditionWrapperBase {

	function get_conditionfailed_layouts($template_id) {
	
		// The layouts and condition failed layouts are the same, the only difference is adding class "hidden" between the 2 states
		return $this->get_layouts($template_id);
	}

	function get_conditionfailed_class($template_id, $atts) {

		$classs = parent::get_conditionfailed_class($template_id, $atts);
		$classs .= ' hidden';
		
		return $classs;
	}

	function get_textfield_template($template_id, $atts) {

		return null;
	}

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'pop-show-notempty');
		if ($textfield_template = $this->get_textfield_template($template_id, $atts)) {

			// Watch out! Class attribute here is called 'textfield-class', so any template implementing the textfield functionality
			// will need to add this class in the span surrounding the data to be refreshed (eg: buttoninner.tmpl)
			$this->append_att($textfield_template, $atts, 'textfield-class', 'pop-show-notempty');
		}
		return parent::init_atts($template_id, $atts);
	}

}
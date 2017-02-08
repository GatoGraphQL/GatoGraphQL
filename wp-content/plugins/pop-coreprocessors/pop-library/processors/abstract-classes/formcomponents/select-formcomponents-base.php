<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SelectFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_SELECT;
	}

	function is_multiple($template_id, $atts) {

		return false;
	}

	function add_label($template_id, $atts) {

		return false;
	}

	function get_compareby($template_id, $atts) {

		return $this->is_multiple($template_id, $atts) ? 'in' : 'eq';
	}

	function get_template_runtimeconfiguration($template_id, $atts) {

		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);
		
		// The value goes into the runtime configuration and not the configuration, so that the configuration can be cached without particular values attached.
		// Eg: calling https://www.mesym.com/add-discussion/?related[]=19373 would initiate the value to 19373 and cache it
		// This way, take all particular stuff to any one URL out from its settings 
		$input = $this->get_input($template_id, $atts);
		$ret['value'] = $input->get_output_value(/*array()*/);

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['disabledvalues'] = $this->get_att($template_id, $atts, 'disabledvalues');

		$multiple = $this->is_multiple($template_id, $atts);
		$addLabel = $this->add_label($template_id, $atts);
	
		// $label = $this->get_label($template_id, $atts);
		$label = $this->get_att($template_id, $atts, 'label');
		$input = $this->get_input($template_id, $atts);
		$options = $addlabel ? $input->get_all_values($label) : $input->get_all_values();

		// $ret['value'] = $input->get_output_value(/*array()*/);

		// Title: either the label or the placeholder, whichever is available
		// $ret['title'] = $ret['label'] ? $ret['label'] : $ret['placeholder'];
		$ret['title'] = $label;
		$ret['options'] = $options;

		if ($multiple) {
			// Add '[]' to the name, coming from the multiselect
			$ret['name'] .= '[]';
		}
		$ret['multiple'] = $multiple;
		$ret['compare-by'] = $this->get_compareby($template_id, $atts);
				
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'disabledvalues', array());

		return parent::init_atts($template_id, $atts);
	}
}

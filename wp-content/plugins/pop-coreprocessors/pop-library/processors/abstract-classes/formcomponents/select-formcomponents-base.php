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

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['disabledvalues'] = $this->get_att($template_id, $atts, 'disabledvalues');

		$multiple = $this->is_multiple($template_id, $atts);
		$addLabel = $this->add_label($template_id, $atts);
	
		// $label = $this->get_label($template_id, $atts);
		$label = $this->get_att($template_id, $atts, 'label');
		$input = $this->get_input($template_id, $atts);
		$options = $addlabel ? $input->get_all_values($label) : $input->get_all_values();

		$ret['value'] = $input->get_output_value(array());

		// Title: either the label or the placeholder, whichever is available
		// $ret['title'] = $ret['label'] ? $ret['label'] : $ret['placeholder'];
		$ret['title'] = $label;
		$ret['options'] = $options;

		if ($multiple) {
			// Add '[]' to the name, coming from the multiselect
			$ret['name'] .= '[]';
			$ret['multiple'] = true;
			$ret['compare-by'] = 'in';
		}
		else {

			$ret['multiple'] = false;
			$ret['compare-by'] = 'eq';
		}
				
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'disabledvalues', array());

		return parent::init_atts($template_id, $atts);
	}
}

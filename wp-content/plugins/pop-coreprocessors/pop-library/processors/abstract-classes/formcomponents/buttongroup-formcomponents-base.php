<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ButtonGroupFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_BUTTONGROUP;
	}

	function get_inputbtn_class($template_id, $atts) {

		return 'btn btn-default';
	}

	function get_inputbtn_classes($template_id, $atts) {

		return array();
	}

	function is_multiple($template_id, $atts) {

		// multiple == true => checkbox type
		// multiple == false => radio type
		return false;
	}

	function get_compareby($template_id, $atts) {

		// multiple == true => checkbox type
		// multiple == false => radio type
		return $this->is_multiple($template_id, $atts) ? 'in' : 'eq';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['disabledvalues'] = $this->get_att($template_id, $atts, 'disabledvalues');

		$multiple = $this->is_multiple($template_id, $atts);
	
		$input = $this->get_input($template_id, $atts);
		$options = $input->get_all_values();
		$ret['value'] = $input->get_output_value(/*array()*/);
		$ret['options'] = $options;
		if ($btnclass = $this->get_att($template_id, $atts, 'btn-class')) {
			$ret[GD_JS_CLASSES/*'classes'*/]['input'] = $btnclass;
		}
		if ($btnclasses = $this->get_att($template_id, $atts, 'btn-classes')) {
			$ret[GD_JS_CLASSES/*'classes'*/]['inputs'] = $btnclasses;
		}

		if ($multiple) {
			// Add '[]' to the name, coming from the multiselect
			$ret['name'] .= '[]';
			$ret['type'] = 'checkbox';
		}
		else {

			$ret['multiple'] = false;
			$ret['type'] = 'radio';
		}
		$ret['compare-by'] = $this->get_compareby($template_id, $atts);
				
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'btn-class', $this->get_inputbtn_class($template_id, $atts));
		$this->add_att($template_id, $atts, 'btn-classes', $this->get_inputbtn_classes($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}
}

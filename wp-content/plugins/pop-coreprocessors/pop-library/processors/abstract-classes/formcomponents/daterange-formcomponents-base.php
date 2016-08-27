<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DateRangeFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_DATERANGE;
	}

	function use_time($template_id, $atts) {

		return false;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);
		
		if ($value = $this->get_value($template_id, $atts)) {
			if ($this->use_time($template_id, $atts)) {
				
				$range = $value['from'] . ' ' . $value['fromtime'] . GD_DATERANGE_SEPARATOR . $value['to'] . ' ' . $value['totime'];
			}
			else {

				$range = $value['from'] . GD_DATERANGE_SEPARATOR . $value['to'];
			}
		}
		$ret['range'] = $range;

		if ($this->use_time($template_id, $atts)) {
				
			$ret['timepicker'] = 'timepicker';
		}

		if ($placeholder = $this->get_att($template_id, $atts, 'placeholder')) {

			$ret['placeholder'] = $placeholder;
		}

		return $ret;				
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'dateRange');
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Use the label as placeholder
		$this->add_att($template_id, $atts, 'placeholder', $this->get_label($template_id, $atts));

		$this->append_att($template_id, $atts, 'class', 'make-daterangepicker');
		
		$this->add_att($template_id, $atts, 'daterange-class', 'opens-right');
		$daterange_class = $this->get_att($template_id, $atts, 'daterange-class');
		$this->append_att($template_id, $atts, 'class', $daterange_class);

		return parent::init_atts($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		if ($this->use_time($template_id, $atts)) {

			$options['subnames'] = array('from', 'to', 'fromtime', 'totime');
		}
		else {

			$options['subnames'] = array('from', 'to');
		}

		return new GD_FormInput_MultipleInputs($options);
	}
}

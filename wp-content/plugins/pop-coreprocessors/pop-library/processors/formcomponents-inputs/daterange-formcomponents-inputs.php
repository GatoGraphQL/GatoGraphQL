<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_DATERANGEPICKER', PoP_TemplateIDUtils::get_template_definition('formcomponent-daterangepicker'));
define ('GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER', PoP_TemplateIDUtils::get_template_definition('formcomponent-daterangetimepicker'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER', PoP_TemplateIDUtils::get_template_definition('date', true)); // Keep the name, so the URL params when filtering make sense

class GD_Template_Processor_DateRangeComponentInputs extends GD_Template_Processor_DateRangeFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_DATERANGEPICKER,
			GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER,
			GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function use_time($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER:

				return true;
		}

		return parent::use_time($template_id, $atts);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_DATERANGEPICKER:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER:
				
				return __('Dates', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER:

				return __('Date/Time', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER:
				
				$ret[] = array('key' => 'value', 'field' => 'daterangetime');
				$ret[] = array('key' => 'range', 'field' => 'daterangetime-string');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DateRangeComponentInputs();
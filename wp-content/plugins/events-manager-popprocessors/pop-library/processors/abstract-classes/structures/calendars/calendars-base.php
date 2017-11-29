<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CalendarsBase extends GD_Template_Processor_StructuresBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CALENDAR;
	}

	function is_critical($template_id, $atts) {

		// Allow to set the value from above
		return $this->get_att($template_id, $atts, 'critical');
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		// Allow to set the calendar to critical: in the homepage in GetPoP, the events may be loading before there is a calendar where to show it, and then it appears all at once, and it looks ugly
		if ($this->is_critical($template_id, $atts)) {

			$this->add_jsmethod($ret, 'calendar', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
		}
		else {

			$this->add_jsmethod($ret, 'calendar');
		}
		return $ret;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);
		
		if ($controlgroup = $this->get_controlgroup($template_id)) {				
			$ret[] = $controlgroup;
		}
		
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
		
		if ($controlgroup = $this->get_controlgroup($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['controlgroup'] = $gd_template_processor_manager->get_processor($controlgroup)->get_settings_id($controlgroup);
		}
		
		return $ret;
	}

	function get_controlgroup($template_id) {

		return GD_TEMPLATE_CALENDARCONTROLGROUP_CALENDAR;
	}

	function get_options($template_id, $atts) {

		return array();
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		global $gd_template_processor_manager;

		$inner = $this->get_inner_template($template_id);
		$ret['layouts'] = $gd_template_processor_manager->get_processor($inner)->get_layouts($inner);
		
		if ($options = $this->get_options($template_id, $atts)) {
			
			$ret['options'] = $options;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'critical', false);
		return parent::init_atts($template_id, $atts);
	}
}

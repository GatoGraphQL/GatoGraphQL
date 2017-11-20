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

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		// The calendar is set to critical, because it is not rendered on the server-side yet
		$this->add_jsmethod($ret, 'calendar', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
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
}

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AppendScriptsLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_APPENDSCRIPT;
	}

	function do_append($template_id) {

		// Through do_append, we can have both success and conditionfailed layouts execute.
		// conditionfailed must also be executed just to remove the class to search for, eg: "pop-append-posts-334"
		return true;
	}

	// function stop_appending($template_id) {

	// 	// Comments will not stop appending, everything else will
	// 	return true;
	// }

	function get_layout_template($template_id) {

		return null;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($this->do_append($template_id)) {
			if ($layout = $this->get_layout_template($template_id)) {
				$ret[] = $layout;
			}
		}

		return $ret;
	}

	function get_operation($template_id, $atts) {
	
		return 'append';
	}

	function get_template_configuration($template_id, $atts) {

		global $gd_template_processor_manager;
	
		$ret = parent::get_template_configuration($template_id, $atts);

		// if ($this->stop_appending($template_id)) {
		
		// 	$ret['stop-appending'] = true;
		// }

		if ($this->do_append($template_id)) {
		
			$ret['do-append'] = true;
			$ret['frame-template'] = $this->get_att($template_id, $atts, 'frame-template');
			$ret['operation'] = $this->get_operation($template_id, $atts);

			if ($layout = $this->get_layout_template($template_id)) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout'] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
			}
		}
		
		return $ret;
	}
}
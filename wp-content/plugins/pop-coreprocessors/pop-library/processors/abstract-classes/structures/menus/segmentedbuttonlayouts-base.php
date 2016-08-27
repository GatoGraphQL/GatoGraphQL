<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SegmentedButtonLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		// return GD_TEMPLATE_LAYOUT_MENU_DROPDOWNSEGMENTEDBUTTON_SOURCE;
		return GD_TEMPLATESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON;
	}

	function get_segmentedbutton_templates($template_id) {

		return array();
	}
	function get_dropdownsegmentedbutton_templates($template_id) {

		return array();
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		$ret = array_merge(
			$ret,
			$this->get_segmentedbutton_templates($template_id),
			$this->get_dropdownsegmentedbutton_templates($template_id)
		);

		return $ret;
	}

	function get_btn_class($template_id, $atts) {
	
		return 'btn btn-default';
	}
	function get_collapse_class($template_id) {
	
		return 'pop-showactive';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// Add the settings-ids of all blocks
		if (!$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]) {
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/] = array();
		}

		$ret[GD_JS_TITLES/*'titles'*/]['toggle'] = __('Toggle menu', 'pop-coreprocessors');
		$ret[GD_JS_CLASSES/*'classes'*/]['btn'] = $this->get_btn_class($template_id, $atts);
		$ret[GD_JS_CLASSES/*'classes'*/]['collapse'] = $this->get_collapse_class($template_id);

		$segmentedbuttons = $this->get_segmentedbutton_templates($template_id);
		$segmentedbuttons_settings_ids = array();
		foreach ($segmentedbuttons as $segmentedbutton) {
			
			$segmentedbutton_settings_id = $gd_template_processor_manager->get_processor($segmentedbutton)->get_settings_id($segmentedbutton);
			$segmentedbuttons_settings_ids[] = $segmentedbutton_settings_id;
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$segmentedbutton_settings_id] = $segmentedbutton;
		}
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['segmentedbuttons'] = $segmentedbuttons_settings_ids;

		$segmentedbuttons = $this->get_dropdownsegmentedbutton_templates($template_id);
		$segmentedbuttons_settings_ids = array();
		foreach ($segmentedbuttons as $segmentedbutton) {
			
			$segmentedbutton_settings_id = $gd_template_processor_manager->get_processor($segmentedbutton)->get_settings_id($segmentedbutton);
			$segmentedbuttons_settings_ids[] = $segmentedbutton_settings_id;
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$segmentedbutton_settings_id] = $segmentedbutton;
		}
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['dropdownsegmentedbuttons'] = $segmentedbuttons_settings_ids;

		return $ret;
	}
}

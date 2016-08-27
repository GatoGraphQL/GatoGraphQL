<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapAddMarkersBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_ADDMARKER;
	}

	function get_modules($template_id) {
	
		return array(
			$this->get_markerscript_template($template_id),
			$this->get_resetmarkerscript_template($template_id)
		);
	}

	function get_markerscript_template($template_id) {
	
		return GD_TEMPLATE_MAP_SCRIPT_MARKERS;
	}

	function get_resetmarkerscript_template($template_id) {
	
		return GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS;
	}

	function get_data_settings($template_id, $atts) {
	
		// Important: Do not bring the data-fields for Add_Marker since they will apply to "post" and not to "location"
		return array();
	}

	function get_template_path($template_id, $atts) {
	
		return $template_id;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$markers = $this->get_markerscript_template($template_id);
		$resetmarkers = $this->get_resetmarkerscript_template($template_id);

		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-markers'] = $gd_template_processor_manager->get_processor($markers)->get_settings_id($markers);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-resetmarkers'] = $gd_template_processor_manager->get_processor($resetmarkers)->get_settings_id($resetmarkers);
		
		return $ret;
	}
}

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapScriptsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_SCRIPT;
	}

	function get_customization_template($template_id) {
	
		return null;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($script_customize = $this->get_customization_template($template_id)) {
			$ret[] = $script_customize;
		}
		// $ret[] = GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS;
		
		return $ret;
	}

	// function get_subcomponent_modules($template_id, $atts) {
	function get_subcomponent_modules($template_id) {
	
		return array(
			'locations' => array(
				'modules' => array(GD_TEMPLATE_MAP_SCRIPT_MARKERS),
				'dataloader' => GD_DATALOADER_LOCATIONLIST
			)
		);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		if ($script_customize = $this->get_customization_template($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-customize'] = $gd_template_processor_manager->get_processor($script_customize)->get_settings_id($script_customize);
		}
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-markers'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_MAP_SCRIPT_MARKERS)->get_settings_id(GD_TEMPLATE_MAP_SCRIPT_MARKERS);
		
		return $ret;
	}	
}
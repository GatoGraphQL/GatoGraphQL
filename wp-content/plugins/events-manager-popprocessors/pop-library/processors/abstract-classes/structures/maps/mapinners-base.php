<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EM_Template_Processor_MapInnersBase extends GD_Template_Processor_StructureInnersBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_INNER;
	}
	
	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		$ret[]= GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS;
		$ret[]= $this->get_drawmarkers_template($template_id);
		
		return $ret;
	}
	
	function get_drawmarkers_template($template_id) {

		//return GD_TEMPLATE_MAP_SCRIPT_DRAWMARKERS;
		return GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS;
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);
		
		$drawmarkers = $this->get_drawmarkers_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-drawmarkers'] = $gd_template_processor_manager->get_processor($drawmarkers)->get_settings_id($drawmarkers);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-resetmarkers'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS)->get_settings_id(GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS);
		
		return $ret;
	}
}
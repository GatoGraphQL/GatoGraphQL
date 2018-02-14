<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapIndividualsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_INDIVIDUAL;
	}

	function get_mapscript_template($template_id) {
	
		return GD_TEMPLATE_MAP_SCRIPT;
	}

	function open_onemarker_infowindow($template_id) {

		return true;
	}

	function get_mapdiv_template($template_id) {
	
		return GD_TEMPLATE_MAP_DIV;
		// return GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_DIV;
	}

	function get_drawmarkers_template($template_id) {
	
		// return GD_TEMPLATE_MAP_SCRIPT_DRAWMARKERS;
		return GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		$ret[] = $this->get_mapscript_template($template_id);
		$ret[] = $this->get_mapdiv_template($template_id);
		$ret[] = GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS;
		$ret[] = $this->get_drawmarkers_template($template_id);
		
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$map_script = $this->get_mapscript_template($template_id);
		$map_div = $this->get_mapdiv_template($template_id);
		$drawmarkers = $this->get_drawmarkers_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script'] = $gd_template_processor_manager->get_processor($map_script)->get_settings_id($map_script);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-div'] = $gd_template_processor_manager->get_processor($map_div)->get_settings_id($map_div);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-drawmarkers'] = $gd_template_processor_manager->get_processor($drawmarkers)->get_settings_id($drawmarkers);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-resetmarkers'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS)->get_settings_id(GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS);
		
		return $ret;
	}	

	function init_atts($template_id, &$atts) {

		$map_div = $this->get_mapdiv_template($template_id);
		$this->add_att($map_div, $atts, 'open-onemarker-infowindow', $this->open_onemarker_infowindow($template_id));
		return parent::init_atts($template_id, $atts);
	}
}
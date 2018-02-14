<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EM_Template_Processor_MapsBase extends GD_Template_Processor_StructuresBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_MAP;
	}
	
	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);	
		$ret[] = $this->get_mapdiv_template($template_id);
		return $ret;
	}	

	function get_mapdiv_template($template_id) {
	
		// return GD_TEMPLATE_MAP_DIV;
		return GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_DIV;
	}
	
	function init_atts($template_id, &$atts) {

		$mapdiv = $this->get_mapdiv_template($template_id);
		$this->merge_block_jsmethod_att($mapdiv, $atts, array('mapStandalone'));
		return parent::init_atts($template_id, $atts);
	}
	
	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		$mapdiv = $this->get_mapdiv_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-div'] = $gd_template_processor_manager->get_processor($mapdiv)->get_settings_id($mapdiv);
		
		return $ret;
	}
}

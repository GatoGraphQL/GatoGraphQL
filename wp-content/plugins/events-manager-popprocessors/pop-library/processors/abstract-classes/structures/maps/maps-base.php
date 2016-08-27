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
		$ret[] = GD_TEMPLATE_MAP_DIV;
		return $ret;
	}	
	
	function init_atts($template_id, &$atts) {

		$this->merge_block_jsmethod_att(GD_TEMPLATE_MAP_DIV, $atts, array('mapStandalone'));
		return parent::init_atts($template_id, $atts);
	}
	
	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-div'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_MAP_DIV)->get_settings_id(GD_TEMPLATE_MAP_DIV);
		return $ret;
	}
}

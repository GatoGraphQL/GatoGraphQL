<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapDrawMarkerScriptsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_SCRIPT_DRAWMARKERS;
	}

	function get_mapdiv_template($template_id, $atts) {
	
		return GD_TEMPLATE_MAP_DIV;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);
		// $ret['mapdiv-template'] = $this->get_att($template_id, $atts, 'mapdiv-template');
		$ret['mapdiv-template'] = $this->get_mapdiv_template($template_id, $atts);
		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	$this->add_att($template_id, $atts, 'mapdiv-template', GD_TEMPLATE_MAP_DIV);
	// 	return parent::init_atts($template_id, $atts);
	// }
}

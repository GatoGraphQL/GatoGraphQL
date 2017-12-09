<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapDivsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_DIV;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		$this->add_jsmethod($ret, 'map');
		
		// // Allow to set the map to critical
		// if ($this->is_critical($template_id, $atts)) {

		// 	$this->add_jsmethod($ret, 'map', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
		// }
		// else {

		// 	$this->add_jsmethod($ret, 'map');
		// }

		return $ret;
	}

	// function is_critical($template_id, $atts) {

	// 	// Allow to set the value from above
	// 	return $this->get_att($template_id, $atts, 'critical');
	// }

	function open_onemarker_infowindow($template_id, $atts) {

		return true;
	}

	function init_atts($template_id, &$atts) {

		// $this->add_att($template_id, $atts, 'critical', false);

		// Open the infoWindow automatically when the map has only 1 marker?
		$this->add_att($template_id, $atts, 'open-onemarker-infowindow', $this->open_onemarker_infowindow($template_id, $atts));
		if ($this->get_att($template_id, $atts, 'open-onemarker-infowindow')) {
			$this->merge_att($template_id, $atts, 'params', array(
				'data-open-onemarker-infowindow' => true
			));
		}
	
		return parent::init_atts($template_id, $atts);
	}
}

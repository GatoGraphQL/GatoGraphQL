<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SidebarsBase extends GD_Template_Processor_ContentsBase {

	function add_fetched_data($template_id, $atts) {
	
		return false;
	}

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'sidebar');
		return parent::init_atts($template_id, $atts);
	}
}
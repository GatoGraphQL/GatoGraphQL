<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CalendarContentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER;
	}

	function get_data_fields($template_id, $atts) {
	
		$ret = parent::get_data_fields($template_id, $atts);
		$ret[] = 'volunteers-needed';
		$ret[] = 'url';
		if ($this->get_att($template_id, $atts, 'show-title')) {
			$ret[] = 'title';
		}
		return $ret;
	}	

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'doNothing', 'void-link');
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($this->get_att($template_id, $atts, 'show-title')) {
			$ret['show-title'] = true;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		// Show the title by default
		$this->add_att($template_id, $atts, 'show-title', true);
		return parent::init_atts($template_id, $atts);
	}
}

<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_NotificationTimeLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONTIME;
	}

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		$ret[] = 'hist-time-nogmt';

		// This one is needed only for the notifications digest, using automated emails
		$ret[] = 'hist-time-formatted-string';
		
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
	
		$this->add_jsmethod($ret, 'timeFromNow');

		return $ret;
	}

	function get_moment_format($template_id, $atts) {

		// Documentation: http://momentjs.com/docs/
		// Unix timestamp
		return 'X';
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		$ret['format'] = $this->get_moment_format($template_id, $atts);

		return $ret;
	}
}
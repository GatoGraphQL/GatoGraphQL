<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_NotificationActionIconLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONICON;
	}

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		// $ret[] = 'action';
		$ret[] = 'icon';
		
		return $ret;
	}

	function get_icon_class($template_id, $atts) {

		return 'fa fa-fw';
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		$ret[GD_JS_CLASSES/*'classes'*/]['icon'] = $this->get_icon_class($template_id, $atts);

		return $ret;
	}
}
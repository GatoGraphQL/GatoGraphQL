<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AnnouncementSpeechBubblesBase extends GD_Template_Processor_SpeechBubblesBase {

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		$ret[GD_JS_CLASSES/*'classes'*/]['bubble-wrapper'] = 'littleguy-announcement';
		
		return $ret;
	}
	
	function init_atts($template_id, &$atts) {

		// Artificial property added to identify the template when adding template-resources
		$this->add_att($template_id, $atts, 'resourceloader', 'littleguy');
		return parent::init_atts($template_id, $atts);
	}
}

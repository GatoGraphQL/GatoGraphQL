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
}

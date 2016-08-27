<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ANNOUNCEMENTSPEECHBUBBLE_STICKY', PoP_ServerUtils::get_template_definition('announcementspeechbubble-sticky'));

class GD_Template_Processor_AnnouncementSpeechBubbles extends GD_Template_Processor_AnnouncementSpeechBubblesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ANNOUNCEMENTSPEECHBUBBLE_STICKY
		);
	}	

	function get_layout($template_id) {

		// return GD_TEMPLATE_LAYOUT_PREVIEWPOST_NOTHUMB_STICKY;
		return GD_TEMPLATE_LAYOUT_FULLVIEW_STICKY;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AnnouncementSpeechBubbles();
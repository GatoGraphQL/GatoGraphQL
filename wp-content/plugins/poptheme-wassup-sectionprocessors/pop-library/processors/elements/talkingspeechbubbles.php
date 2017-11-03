<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TALKINGSPEECHBUBBLE_THOUGHT', PoP_TemplateIDUtils::get_template_definition('talkingspeechbubble-thought'));

class GD_Template_Processor_CustomTalkingSpeechBubbles extends GD_Template_Processor_TalkingSpeechBubblesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TALKINGSPEECHBUBBLE_THOUGHT
		);
	}	

	function get_layout($template_id) {

		return GD_TEMPLATE_LAYOUT_FULLVIEW_THOUGHT;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomTalkingSpeechBubbles();
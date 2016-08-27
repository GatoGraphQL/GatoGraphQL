<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MessageFeedbacksBase extends GD_Template_Processor_StructuresBase {

	function get_template_source($template_id) {
	
		return GD_TEMPLATESOURCE_MESSAGEFEEDBACK;
	}
}
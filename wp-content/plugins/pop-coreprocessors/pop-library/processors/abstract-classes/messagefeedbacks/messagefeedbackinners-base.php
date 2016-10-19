<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MessageFeedbackInnersBase extends GD_Template_Processor_StructureInnersBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MESSAGEFEEDBACK_INNER;
	}
}
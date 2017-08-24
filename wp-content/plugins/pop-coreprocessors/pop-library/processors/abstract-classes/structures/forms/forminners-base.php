<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormInnersBase extends GD_Template_Processor_StructureInnersBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_FORM_INNER;
	}

	function get_template_cb_actions($template_id, $atts) {
	
		// The form inner template, execute it only when doing init-lazy, eg: Edit Individual Profile
		// Otherwise do not re-merge it, no need for the form
		return array(
			GD_TEMPLATECALLBACK_ACTION_LOADCONTENT,
			GD_TEMPLATECALLBACK_ACTION_REFETCH,
			GD_TEMPLATECALLBACK_ACTION_RESET,
		);
	}
}

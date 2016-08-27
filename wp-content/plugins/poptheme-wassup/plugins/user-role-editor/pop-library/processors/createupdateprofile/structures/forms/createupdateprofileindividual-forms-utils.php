<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Custom_Template_Processor_CreateUpdateProfileIndividualFormsUtils {

	static function get_components($template_id, &$components, $processor) {

		// Add extra components
		$extra_components_int = array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_INDIVIDUALINTERESTS
		);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_SHORTDESCRIPTION, $components), 0, $extra_components_int);
	}
}
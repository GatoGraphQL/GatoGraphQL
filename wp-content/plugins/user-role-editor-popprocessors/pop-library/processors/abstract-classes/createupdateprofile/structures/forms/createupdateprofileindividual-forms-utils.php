<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateUpdateProfileIndividualFormsUtils {

	static function get_components($template_id, &$components, $processor) {

		// Add extra components
		$extra_components_name = array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_LASTNAME
		);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_FIRSTNAME, $components)+1, 0, $extra_components_name);
	}
}
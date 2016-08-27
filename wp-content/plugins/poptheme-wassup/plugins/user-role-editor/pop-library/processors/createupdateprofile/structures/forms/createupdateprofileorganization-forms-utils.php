<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Custom_Template_Processor_CreateUpdateProfileOrganizationFormsUtils {

	static function get_components($template_id, &$components, $processor) {

		// Add extra components
		$extra_components_cattype = array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONTYPES,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES
		);
		$extra_components_contact = array(
			// GD_URE_TEMPLATE_FORMCOMPONENT_CUP_YEARSTARTED,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTNUMBER,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTPERSON
		);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_SHORTDESCRIPTION, $components), 0, $extra_components_cattype);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL, $components), 0, $extra_components_contact);
	}
}
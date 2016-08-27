<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateUpdateProfileFormsUtils {

	static function get_components($template_id, &$components, $processor) {

		// Add extra components
		$extra_components_beforedesc = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_SHORTDESCRIPTION,
		);
		// $extra_components_afterdesc = array(
		// 	GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP
		// );
		$extra_components_contactinfo = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_DISPLAYEMAIL
		);
		$extra_components_socialmedia = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_FACEBOOK,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_TWITTER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_LINKEDIN,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_YOUTUBE
		);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION, $components), 0, $extra_components_beforedesc);
		// array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL, $components), 0, $extra_components_contactinfo);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL, $components)+1, 0, $extra_components_contactinfo);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL, $components)+1, 0, $extra_components_socialmedia);
	}
}
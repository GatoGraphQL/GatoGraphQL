<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions for URE under the Template Manager
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add a template to include Community members in the filter
add_filter('GD_Template_Processor_UserTypeaheadSelectedLayoutsBase:get_extra_templates', 'gd_ure_usertypeaheadselectedlayout_addcommunitytemplate', 10, 2);
function gd_ure_usertypeaheadselectedlayout_addcommunitytemplate($extra_templates, $template_id) {

	if ($template_id == GD_TEMPLATE_LAYOUTUSER_FILTERTYPEAHEAD_SELECTED) {

		$extra_templates[] = GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY;
	}
	return $extra_templates;
}
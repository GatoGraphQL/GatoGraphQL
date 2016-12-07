<?php

/**---------------------------------------------------------------------------------------------------------------
 * Social Media
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CreateUpdateProfileFormsUtils:socialmedia', 'au_socialmedia');
function au_socialmedia($components) {

	// Remove LinkedIn
	array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_LINKEDIN, $components), 1);

	return $components;
}

/**---------------------------------------------------------------------------------------------------------------
 * Replace Organization Types/Categories with Índole/Jurisdicciones
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_URE_Template_Processor_MultiSelectFormComponentInputs:label:types', 'au_label_types');
function au_label_types($label) {

	// return __('Organization Nature', 'agendaurbana-processors');
	return __('Nature', 'agendaurbana-processors');
}
add_filter('GD_URE_Template_Processor_MultiSelectFormComponentInputs:label:categories', 'au_label_categories');
function au_label_categories($label) {

	// return __('Organization Jurisdictions', 'agendaurbana-processors');
	return __('Jurisdiction', 'agendaurbana-processors');
}
add_filter('GD_URE_Custom_Template_Processor_ProfileOrganizationLayoutsBase:titles', 'au_organizationlayout_titles');
function au_organizationlayout_titles($titles) {

	$titles['types'] = __('Nature', 'agendaurbana-processors');
	$titles['categories'] = __('Jurisdiction', 'agendaurbana-processors');
	return $titles;
}

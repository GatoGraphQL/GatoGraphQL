<?php

/**---------------------------------------------------------------------------------------------------------------
 * Layout templates
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_EM_Template_Processor_MultipleUserLayouts:layouts:mapdetails', 'gd_ure_em_layouttemplates_mapdetails');
function gd_ure_em_layouttemplates_mapdetails($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_MAPDETAILS,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_MAPDETAILS,
		)
	);
}
add_filter('GD_EM_Template_Processor_MultipleUserLayouts:layouts:authormapdetails', 'gd_ure_em_layouttemplates_authormapdetails');
function gd_ure_em_layouttemplates_authormapdetails($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_ORGANIZATION_MAPDETAILS,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_INDIVIDUAL_MAPDETAILS,
		)
	);
}

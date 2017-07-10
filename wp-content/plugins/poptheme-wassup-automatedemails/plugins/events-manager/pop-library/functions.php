<?php
/**---------------------------------------------------------------------------------------------------------------
 * Layout templates
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:details', 'gd_ae_em_layouttemplates_details');
function gd_ae_em_layouttemplates_details($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS,
		)
	);
}
add_filter('PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:thumbnail', 'gd_ae_em_layouttemplates_thumbnail');
function gd_ae_em_layouttemplates_thumbnail($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL,
		)
	);
}
add_filter('PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:list', 'gd_ae_em_layouttemplates_list');
function gd_ae_em_layouttemplates_list($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST,
		)
	);
}
add_filter('PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:simpleview', 'gd_ae_em_layouttemplates_simpleview');
function gd_ae_em_layouttemplates_simpleview($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_SIMPLEVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_SIMPLEVIEW,
		)
	);
}
add_filter('PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:fullview', 'gd_ae_em_layouttemplates_fullview');
function gd_ae_em_layouttemplates_fullview($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT,
		)
	);
}
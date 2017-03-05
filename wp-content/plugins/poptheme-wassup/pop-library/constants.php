<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Constants
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_IDS_APPSTATUS', 'app-status');
define ('GD_INTERCEPT_TARGET_NAVIGATOR', 'navigator');

add_filter('gd_jquery_constants', 'gd_themewassup_jquery_constants_templatemanager_impl');
function gd_themewassup_jquery_constants_templatemanager_impl($jquery_constants) {

	// $jquery_constants['INTERCEPT_TARGET_NAVIGATOR'] = GD_INTERCEPT_TARGET_NAVIGATOR;

	// Website Status ID
	$jquery_constants['IDS_APPSTATUS'] = POP_IDS_APPSTATUS;

	// pageSection IDs
	$jquery_constants['PS_MAIN_ID'] = GD_TEMPLATEID_PAGESECTIONID_MAIN;
	$jquery_constants['PS_TOP_ID'] = GD_TEMPLATEID_PAGESECTIONID_TOP;
	$jquery_constants['PS_QUICKVIEW_ID'] = GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN;
	$jquery_constants['PS_QUICKVIEWINFO_ID'] = GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWSIDEINFO;
	$jquery_constants['PS_PAGETABS_ID'] = GD_TEMPLATEID_PAGESECTIONID_PAGETABS;
	$jquery_constants['PS_FRAME_SIDE_ID'] = GD_TEMPLATEID_PAGESECTIONID_SIDE;
	$jquery_constants['PS_FRAME_NAVIGATOR_ID'] = GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR;
	$jquery_constants['PS_SIDEINFO_ID'] = GD_TEMPLATEID_PAGESECTIONID_SIDEINFO;
	$jquery_constants['PS_HOVER_ID'] = GD_TEMPLATEID_PAGESECTIONID_HOVER;
	$jquery_constants['PS_ADDONS_ID'] = GD_TEMPLATEID_PAGESECTIONID_ADDONS;
	$jquery_constants['PS_ADDONTABS_ID'] = GD_TEMPLATEID_PAGESECTIONID_ADDONTABS;
	$jquery_constants['PS_MODALS_ID'] = GD_TEMPLATEID_PAGESECTIONID_MODALS;

	return $jquery_constants;
}

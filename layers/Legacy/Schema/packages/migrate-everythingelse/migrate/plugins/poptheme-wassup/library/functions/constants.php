<?php
use PoP\Hooks\Facades\HooksAPIFacade;

define('POP_IDS_APPSTATUS', 'app-status');
define('GD_INTERCEPT_TARGET_NAVIGATOR', 'navigator');

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdThemewassupJqueryConstantsModulemanagerImpl');
function gdThemewassupJqueryConstantsModulemanagerImpl($jqueryConstants)
{

    // $jqueryConstants['INTERCEPT_TARGET_NAVIGATOR'] = GD_INTERCEPT_TARGET_NAVIGATOR;

    // Website Status ID
    $jqueryConstants['IDS_APPSTATUS'] = POP_IDS_APPSTATUS;

    // pageSection IDs
    $jqueryConstants['PS_MAIN_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_BODY;
    $jqueryConstants['PS_TOP_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_TOP;
    $jqueryConstants['PS_QUICKVIEW_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEW;
    $jqueryConstants['PS_QUICKVIEWINFO_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEWSIDEINFO;
    $jqueryConstants['PS_PAGETABS_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_BODYTABS;
    $jqueryConstants['PS_FRAME_SIDE_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_SIDE;
    $jqueryConstants['PS_FRAME_NAVIGATOR_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR;
    $jqueryConstants['PS_SIDEINFO_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_BODYSIDEINFO;
    $jqueryConstants['PS_HOVER_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_HOVER;
    $jqueryConstants['PS_ADDONS_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_ADDONS;
    $jqueryConstants['PS_ADDONTABS_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_ADDONTABS;
    $jqueryConstants['PS_MODALS_ID'] = POP_MODULEID_PAGESECTIONCONTAINERID_MODALS;

    return $jqueryConstants;
}

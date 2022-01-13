<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsLocations');
function gdJqueryConstantsLocations($jqueryConstants)
{
    $jqueryConstants['LOCATIONSID_FIELDNAME'] = POP_INPUTNAME_LOCATIONID;
    return $jqueryConstants;
}

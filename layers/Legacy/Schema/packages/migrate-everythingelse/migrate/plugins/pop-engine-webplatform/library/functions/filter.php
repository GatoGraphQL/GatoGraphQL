<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsForminputInputImpl');
function gdJqueryConstantsForminputInputImpl($jqueryConstants)
{
    $jqueryConstants['FORM_INPUT'] = GD_FORM_INPUT;
    // $jqueryConstants['FILTER_NAME_INPUT'] = POP_FILTER_NAME_INPUT;
    return $jqueryConstants;
}

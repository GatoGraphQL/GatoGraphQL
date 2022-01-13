<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsForminputInputImpl');
function gdJqueryConstantsForminputInputImpl($jqueryConstants)
{
    $jqueryConstants['FORM_INPUT'] = GD_FORM_INPUT;
    // $jqueryConstants['FILTER_NAME_INPUT'] = POP_FILTER_NAME_INPUT;
    return $jqueryConstants;
}

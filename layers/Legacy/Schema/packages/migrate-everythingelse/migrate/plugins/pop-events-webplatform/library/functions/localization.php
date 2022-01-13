<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdEmJqueryConstantsUrlparams');
function gdEmJqueryConstantsUrlparams($jqueryConstants)
{
    $jqueryConstants['URLPARAM_YEAR'] = GD_URLPARAM_YEAR;
    $jqueryConstants['URLPARAM_MONTH'] = GD_URLPARAM_MONTH;
    
    return $jqueryConstants;
}

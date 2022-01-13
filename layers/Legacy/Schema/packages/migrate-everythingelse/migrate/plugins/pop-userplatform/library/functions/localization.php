<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popUserplatformJqueryConstants');
function popUserplatformJqueryConstants($jqueryConstants)
{
    $jqueryConstants['USERATTRIBUTES'] = gdUserAttributes();
    return $jqueryConstants;
}

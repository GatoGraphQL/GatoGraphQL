<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_PARAMS_PREVIEW', 'preview');
define('POP_PARAMS_PATH', 'path');

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'pppPopWebPlatformJqueryConstants');
function pppPopWebPlatformJqueryConstants($jqueryConstants)
{
    $jqueryConstants['PARAMS_PREVIEW'] = POP_PARAMS_PREVIEW;
    $jqueryConstants['PARAMS_PATH'] = POP_PARAMS_PATH;
    return $jqueryConstants;
}

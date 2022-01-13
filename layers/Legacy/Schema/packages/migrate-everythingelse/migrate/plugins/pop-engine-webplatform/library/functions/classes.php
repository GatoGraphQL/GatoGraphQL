<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_CLASS_LOADINGCONTENT', 'pop-loadingcontent');
define('POP_CLASSPREFIX_MERGE', 'pop-merge-');

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'popWebPlatformJqueryClasses');
function popWebPlatformJqueryClasses($jqueryConstants)
{
    $jqueryConstants['CLASS_LOADINGCONTENT'] = POP_CLASS_LOADINGCONTENT;
    $jqueryConstants['CLASSPREFIX_MERGE'] = POP_CLASSPREFIX_MERGE;
    return $jqueryConstants;
}

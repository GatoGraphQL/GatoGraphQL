<?php

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsSparesourceloaderjsparams');
function gdJqueryConstantsSparesourceloaderjsparams($jqueryConstants)
{
    $jqueryConstants['JS_RESOURCES'] = GD_JS_RESOURCES;

    return $jqueryConstants;
}

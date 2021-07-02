<?php
use PoP\Hooks\Facades\HooksAPIFacade;

$compact = \PoP\ComponentModel\Environment::compactResponseJsonKeys();
define('GD_JS_FONTAWESOME', $compact ? 'fa' : 'fontawesome');

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'wassupJqueryConstantsJsparams');
function wassupJqueryConstantsJsparams($jqueryConstants)
{
    $jqueryConstants['JS_FONTAWESOME'] = GD_JS_FONTAWESOME;

    return $jqueryConstants;
}

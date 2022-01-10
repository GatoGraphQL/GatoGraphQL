<?php
use PoP\Hooks\Facades\HooksAPIFacade;

define('POP_PARAMS_PARAMSSCOPE_URL', 'paramsscope-url');

define('POP_PROGRESSIVEBOOTING_CRITICAL', \PoP\ComponentModel\Environment::compactResponseJsonKeys() ? 'c' : 'critical');
define('POP_PROGRESSIVEBOOTING_NONCRITICAL', \PoP\ComponentModel\Environment::compactResponseJsonKeys() ? 'n' : 'noncritical');

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popWebPlatformJqueryConstantsImpl');
function popWebPlatformJqueryConstantsImpl($jqueryConstants)
{
    $jqueryConstants['SPINNER'] = GD_CONSTANT_LOADING_SPINNER;
    $jqueryConstants['LOADING_MSG'] = POP_LOADING_MSG;
    $jqueryConstants['VALUES_DEFAULT'] = \PoP\ConfigurationComponentModel\Constants\Values::DEFAULT;
    $jqueryConstants['PARAMS_PARAMSSCOPE_URL'] = POP_PARAMS_PARAMSSCOPE_URL;

    // Comment Leo20/11/2017: add these constants always, since they are referenced in the JS code even if Progressive Booting is not enabled
    // if (PoP_WebPlatform_ServerUtils::useProgressiveBooting()) {
    $jqueryConstants['PROGRESSIVEBOOTING'] = array(
        'CRITICAL' => POP_PROGRESSIVEBOOTING_CRITICAL,
        'NONCRITICAL' => POP_PROGRESSIVEBOOTING_NONCRITICAL,
    );
    // }

    return $jqueryConstants;
}

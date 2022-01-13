<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popResourceloaderJqueryConstants');
function popResourceloaderJqueryConstants($jqueryConstants)
{
    $jqueryConstants['SEPARATOR_RESOURCELOADER'] = GD_SEPARATOR_RESOURCELOADER;

    if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
        $jqueryConstants['USECODESPLITTING'] = true;
        $jqueryConstants['CODESPLITTING']['PREFIXES'] = array(
            'FORMAT' => POP_RESOURCELOADERIDENTIFIER_FORMAT,
            'ROUTE' => POP_RESOURCELOADERIDENTIFIER_ROUTE,
            'TARGET' => POP_RESOURCELOADERIDENTIFIER_TARGET,
        );
    } else {
        $jqueryConstants['USECODESPLITTING'] = '';
    }

    $jqueryConstants['PRINTTAGSINBODY'] = PoP_ResourceLoader_ServerUtils::includeResourcesInBody() ? true : '';

    return $jqueryConstants;
}

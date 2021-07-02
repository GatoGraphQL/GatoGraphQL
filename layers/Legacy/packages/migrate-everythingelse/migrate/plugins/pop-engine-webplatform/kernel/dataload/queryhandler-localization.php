<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Application\ModuleProcessors\DataloadingConstants;
use PoP\Application\QueryInputOutputHandlers\ParamConstants;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsQueryhandler');
function gdJqueryConstantsQueryhandler($jqueryConstants)
{
    $jqueryConstants['DATALOAD_PARAMS'] = ParamConstants::PARAMS;
    $jqueryConstants['DATALOAD_VISIBLEPARAMS'] = ParamConstants::VISIBLEPARAMS;
    $jqueryConstants['DATALOAD_PUSHURLATTS'] = ParamConstants::PUSHURLATTS;
    $jqueryConstants['DATALOAD_LAZYLOAD'] = DataloadingConstants::LAZYLOAD;
    $jqueryConstants['DATALOAD_EXTERNALLOAD'] = DataloadingConstants::EXTERNALLOAD;

    return $jqueryConstants;
}

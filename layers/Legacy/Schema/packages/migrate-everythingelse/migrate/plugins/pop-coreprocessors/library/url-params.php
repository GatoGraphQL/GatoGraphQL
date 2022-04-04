<?php
use PoP\Root\Constants\Params;

const GD_URLPARAM_INTERCEPTURLS = 'intercept-urls';
const GD_URLPARAM_TITLE = 'title';
const GD_URLPARAM_TITLELINK = 'title-link';
const GD_URLPARAM_STOPFETCHING = 'stop-fetching';

define('GD_URLPARAM_ACTION_PRINT', 'print');

define('GD_URLPARAM_TARGET_PRINT', 'print');
define('GD_URLPARAM_TARGET_SOCIALMEDIA', 'socialmedia');

\PoP\Root\App::addFilter('gd_jquery_constants', gdJqueryConstantsUrlparams(...));
function gdJqueryConstantsUrlparams($jqueryConstants)
{
    $jqueryConstants['UNIQUEID'] = \PoP\ComponentModel\Constants\Response::UNIQUE_ID;

    $jqueryConstants['URLPARAM_TIMESTAMP'] = GD_URLPARAM_TIMESTAMP;
    $jqueryConstants['URLPARAM_ACTIONS'] = \PoP\ComponentModel\Constants\Params::ACTIONS;
    $jqueryConstants['URLPARAM_ACTION_LATEST'] = GD_URLPARAM_ACTION_LOADLATEST;
    $jqueryConstants['URLPARAM_ACTION_PRINT'] = GD_URLPARAM_ACTION_PRINT;

    $jqueryConstants['URLPARAM_TITLE'] = GD_URLPARAM_TITLE;
    $jqueryConstants['URLPARAM_TITLELINK'] = GD_URLPARAM_TITLELINK;
    $jqueryConstants['URLPARAM_URL'] = \PoP\ComponentModel\Constants\Response::URL;
    $jqueryConstants['URLPARAM_ERROR'] = \PoP\ComponentModel\Constants\Response::ERROR;
    $jqueryConstants['URLPARAM_SILENTDOCUMENT'] = GD_URLPARAM_SILENTDOCUMENT;
    $jqueryConstants['URLPARAM_STORELOCAL'] = GD_URLPARAM_STORELOCAL;
    $jqueryConstants['URLPARAM_NONCES'] = GD_URLPARAM_NONCES;

    $jqueryConstants['URLPARAM_BACKGROUNDLOADURLS'] = \PoP\ComponentModel\Constants\Response::BACKGROUND_LOAD_URLS;
    $jqueryConstants['URLPARAM_INTERCEPTURLS'] = GD_URLPARAM_INTERCEPTURLS;

    $jqueryConstants['URLPARAM_OUTPUT'] = \PoP\ComponentModel\Constants\Params::OUTPUT;
    $jqueryConstants['URLPARAM_OUTPUT_JSON'] = \PoP\ComponentModel\Constants\Outputs::JSON;

    $jqueryConstants['URLPARAM_PAGED'] = \PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER;
    $jqueryConstants['URLPARAM_OPERATION_APPEND'] = GD_URLPARAM_OPERATION_APPEND;
    $jqueryConstants['URLPARAM_OPERATION_PREPEND'] = GD_URLPARAM_OPERATION_PREPEND;
    $jqueryConstants['URLPARAM_OPERATION_REPLACE'] = GD_URLPARAM_OPERATION_REPLACE;
    $jqueryConstants['URLPARAM_OPERATION_REPLACEINLINE'] = GD_URLPARAM_OPERATION_REPLACEINLINE;

    $jqueryConstants['URLPARAM_FORMAT'] = \PoP\ConfigurationComponentModel\Constants\Params::FORMAT;
    $jqueryConstants['URLPARAM_ROUTE'] = Params::ROUTE;

    $jqueryConstants['URLPARAM_DATAOUTPUTITEMS'] = \PoP\ComponentModel\Constants\Params::DATA_OUTPUT_ITEMS;
    $jqueryConstants['URLPARAM_DATAOUTPUTITEMS_META'] = \PoP\ComponentModel\Constants\DataOutputItems::META;
    $jqueryConstants['URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS'] = \PoP\ConfigurationComponentModel\Constants\DataOutputItems::MODULESETTINGS;
    $jqueryConstants['URLPARAM_DATAOUTPUTITEMS_MODULEDATA'] = \PoP\ComponentModel\Constants\DataOutputItems::MODULE_DATA;
    $jqueryConstants['URLPARAM_DATAOUTPUTITEMS_DATABASES'] = \PoP\ComponentModel\Constants\DataOutputItems::DATABASES;
    $jqueryConstants['URLPARAM_DATAOUTPUTITEMS_SESSION'] = \PoP\ComponentModel\Constants\DataOutputItems::SESSION;

    $jqueryConstants['URLPARAM_TARGET'] = \PoP\ConfigurationComponentModel\Constants\Params::TARGET;
    $jqueryConstants['URLPARAM_TARGET_MAIN'] = \PoP\ConfigurationComponentModel\Constants\Targets::MAIN;
    $jqueryConstants['URLPARAM_TARGET_FULL'] = GD_URLPARAM_TARGET_FULL;
    $jqueryConstants['URLPARAM_TARGET_PRINT'] = GD_URLPARAM_TARGET_PRINT;
    $jqueryConstants['URLPARAM_TARGET_SOCIALMEDIA'] = GD_URLPARAM_TARGET_SOCIALMEDIA;

    $jqueryConstants['URLPARAM_STOPFETCHING'] = GD_URLPARAM_STOPFETCHING;

    $jqueryConstants['DONOTRENDER'] = POP_JS_DONOTRENDER;
    $jqueryConstants['ISMULTIPLEOPEN'] = POP_JS_ISMULTIPLEOPEN;

    return $jqueryConstants;
}

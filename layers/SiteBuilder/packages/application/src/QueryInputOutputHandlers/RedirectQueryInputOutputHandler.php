<?php

declare(strict_types=1);

namespace PoP\Application\QueryInputOutputHandlers;

use PoP\Root\App;
use PoP\Application\Constants\Response;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;

class RedirectQueryInputOutputHandler extends ActionExecutionQueryInputOutputHandler
{
    // function prepareQueryArgs(&$query_args) {

    //     parent::prepareQueryArgs($query_args);

    //     // Add the Redirect to Param. If there is none, use the referrer.
    //     // This is useful when coming from the Login link above the Template, which can't pass the 'redirect_to' data
    //     $query_args[\PoP\Application\Constants\Response::REDIRECT_TO] = $query_args[\PoP\Application\Constants\Response::REDIRECT_TO] ?? \PoP\Root\App::server('HTTP_REFERER');
    // }

    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        // Add the Redirect to Param. If there is none, use the referrer.
        // This is useful when coming from the Login link above the Template, which can't pass the 'redirect_to' data
        $ret[Response::REDIRECT_TO] = $query_args[Response::REDIRECT_TO] ?? App::server('HTTP_REFERER');

        return $ret;
    }

    // function getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     $query_args = $data_properties[ParamConstants::QUERYARGS];

    //     // Add the Redirect to
    //     $ret[ParamConstants::PARAMS][\PoP\Application\Constants\Response::REDIRECT_TO] = $query_args[\PoP\Application\Constants\Response::REDIRECT_TO];

    //     return $ret;
    // }

    // function getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     $query_args = $data_properties[ParamConstants::QUERYARGS];

    //     // Add the Redirect to
    //     $ret[ParamConstants::PARAMS][\PoP\Application\Constants\Response::REDIRECT_TO] = $query_args[\PoP\Application\Constants\Response::REDIRECT_TO];

    //     return $ret;
    // }
}

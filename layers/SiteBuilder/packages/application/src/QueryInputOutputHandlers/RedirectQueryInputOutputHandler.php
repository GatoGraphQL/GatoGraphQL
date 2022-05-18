<?php

declare(strict_types=1);

namespace PoP\Application\QueryInputOutputHandlers;

use PoP\Application\Constants\Response;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\Root\App;

class RedirectQueryInputOutputHandler extends ActionExecutionQueryInputOutputHandler
{
    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        // Add the Redirect to Param. If there is none, use the referrer.
        // This is useful when coming from the Login link above the Template, which can't pass the 'redirect_to' data
        $ret[Response::REDIRECT_TO] = $query_args[Response::REDIRECT_TO] ?? App::server('HTTP_REFERER');

        return $ret;
    }
}

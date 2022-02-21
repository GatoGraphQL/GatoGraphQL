<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryInputOutputHandlers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;

abstract class AbstractQueryInputOutputHandler implements QueryInputOutputHandlerInterface
{
    public function prepareQueryArgs(&$query_args): void
    {
    }
    public function getQueryState(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        return array();
    }
    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        return array();
    }
    public function getQueryResult(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        return array();
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryInputOutputHandlers;

use PoP\Root\Feedback\FeedbackItemResolution;

interface QueryInputOutputHandlerInterface
{
    /**
     * @param array<string,mixed> $query_args
     */
    public function prepareQueryArgs(array &$query_args): void;
    public function getQueryState(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array;
    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array;
    public function getQueryResult(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array;
}

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
    /**
     * @return array<string,mixed>
     */
    public function getQueryState(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array;
    /**
     * @return array<string,mixed>
     */
    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array;
    /**
     * @return array<string,mixed>
     */
    public function getQueryResult(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array;
}

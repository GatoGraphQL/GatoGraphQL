<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryInputOutputHandlers;

abstract class AbstractQueryInputOutputHandler implements QueryInputOutputHandlerInterface
{
    public function prepareQueryArgs(&$query_args): void
    {
    }
    public function getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        return array();
    }
    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        return array();
    }
    public function getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        return array();
    }
}

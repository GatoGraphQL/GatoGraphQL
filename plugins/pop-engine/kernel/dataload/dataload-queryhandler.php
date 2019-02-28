<?php
namespace PoP\Engine;

abstract class QueryHandlerBase
{
    public function __construct()
    {
        $queryhandler_manager = QueryHandler_Manager_Factory::getInstance();
        $queryhandler_manager->add($this->getName(), $this);
    }

    abstract public function getName();

    public function prepareQueryArgs(&$query_args)
    {
    }

    public function getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        return array();
    }
    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        return array();
    }
    public function getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        return array();
    }
}

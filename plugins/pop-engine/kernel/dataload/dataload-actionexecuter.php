<?php
namespace PoP\Engine;

abstract class ActionExecuterBase
{
    public function __construct()
    {
        $gd_dataload_actionexecution_manager = ActionExecution_Manager_Factory::getInstance();
        $gd_dataload_actionexecution_manager->addActionexecutioner($this->getName(), $this);
    }

    abstract public function getName();

    public function execute(&$data_properties)
    {
        return null;
    }
}

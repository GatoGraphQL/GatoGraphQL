<?php
namespace PoP\Engine;

class ActionExecution_Manager
{
    public $executioners = [];
    public $results = [];
    
    public function __construct()
    {
        ActionExecution_Manager_Factory::setInstance($this);

        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            'inferVarsProperties',
            array($this, 'reset')
        );
    }

    public function reset()
    {
        $this->results = [];
    }
    
    public function addActionexecutioner($name, $executioner)
    {
        $this->executioners[$name] = $executioner;
    }
    
    public function getActionexecuter($name)
    {
        $actionexecuter = $this->executioners[$name];
        if (!$actionexecuter) {
            throw new \Exception(sprintf('No Action Executer with name \'%s\' (%s)', $name, fullUrl()));
        }

        return $actionexecuter;
    }
    
    public function setResult($name, $result)
    {
        $this->results[$name] = $result;
    }
    
    public function getResult($name)
    {
        return $this->results[$name];
    }
}
    
/**
 * Initialize
 */
new ActionExecution_Manager();

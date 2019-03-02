<?php
namespace PoP\Engine;

class ActionExecution_Manager
{
    public $executioners;
    public $results;
    
    public function __construct()
    {
        ActionExecution_Manager_Factory::setInstance($this);
        $this->executioners = $this->results = array();

        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            '\PoP\Engine\Engine:generateData:reset',
            array($this, 'reset')
        );
    }

    public function reset()
    {
        $this->results = array();
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

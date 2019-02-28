<?php
namespace PoP\Engine;

class FieldProcessor_Manager
{
    public $fieldprocessors;
    
    public function __construct()
    {
        FieldProcessor_Manager_Factory::setInstance($this);
        return $this->fieldprocessors = array();
    }
    
    public function add($name, $fieldprocessor)
    {
        $this->fieldprocessors[$name] = $fieldprocessor;
    }
    
    public function get($name)
    {
        return $this->fieldprocessors[$name];
    }
}
    
/**
 * Initialize
 */
new FieldProcessor_Manager();

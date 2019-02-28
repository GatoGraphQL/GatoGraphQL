<?php
namespace PoP\Engine;

class DataStructureFormat_Manager
{
    public $formatters;
    public $default_name;
    
    public function __construct()
    {
        DataStructureFormat_Manager_Factory::setInstance($this);
        return $this->formatters = array();
    }
    
    public function add($name, $formatter)
    {
        $this->formatters[$name] = $formatter;
    }

    public function setDefault(&$formatter)
    {
        $this->default_name = $formatter->getName();
    }
    
    public function getDatastructureFormatter($name)
    {

        // Return the formatter if it exists
        if ($this->formatters[$name]) {
            return $this->formatters[$name];
        };
        
        // Return the default one
        if ($this->default_name) {
            return $this->formatters[$this->default_name];
        }

        return null;
    }
}
    
/**
 * Initialize
 */
new DataStructureFormat_Manager();

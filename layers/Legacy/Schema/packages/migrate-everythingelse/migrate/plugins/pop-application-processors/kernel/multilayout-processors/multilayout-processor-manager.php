<?php

class PoP_Application_MultilayoutManager
{
    public $processors;
    public $multilayouts;
    
    public function __construct()
    {
        $this->processors = $this->multilayouts = array();
        PoP_Application_MultilayoutManagerFactory::setInstance($this);
    }
    
    public function add(PoP_Application_Multilayout $processor)
    {
        $this->processors[] = $processor;
    }

    public function getLayoutComponents($handle, $format = '')
    {
        // First check if the results have been cached
        $key = $handle.($format ? '-'.$format : '');
        $layouts = $this->multilayouts[$key];
        if (!is_null($layouts)) {
            return $layouts;
        }
    
        $layouts = array();
        foreach ($this->processors as $processor) {
            $processor->addLayoutComponents($layouts, $handle, $format);
        }

        // Cache the results
        $this->multilayouts[$key] = $layouts;

        return $layouts;
    }
}
    
/**
 * Initialize
 */
new PoP_Application_MultilayoutManager();

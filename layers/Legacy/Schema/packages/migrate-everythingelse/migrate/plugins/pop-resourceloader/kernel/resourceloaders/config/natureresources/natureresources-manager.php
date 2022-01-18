<?php

class PoP_ResourceLoader_NatureResourcesManager {

    var $processors;
    
    public function __construct() {
    
        $this->processors = array();
        PoP_ResourceLoader_NatureResourcesManagerFactory::setInstance($this);
    }
    
    function add(PoP_ResourceLoader_NatureResources $processor) {
    
        $this->processors[] = $processor;     
    }

    function getHomeResources($modulefilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addHomeResources($resources, $modulefilter, $options);
        }

        return $resources;
    }
    
    function getAuthorResources($modulefilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addAuthorResources($resources, $modulefilter, $options);
        }

        return $resources;
    }
    
    function getTagResources($modulefilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addTagResources($resources, $modulefilter, $options);
        }

        return $resources;
    }       
    
    function get404Resources($modulefilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->add404Resources($resources, $modulefilter, $options);
        }

        return $resources;
    }

    function getSingleResources($modulefilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addSingleResources($resources, $modulefilter, $options);
        }

        return $resources;
    }
    
    function getPageResources($modulefilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addPageResources($resources, $modulefilter, $options);
        }

        return $resources;
    }
    
    function getGenericNatureResources($modulefilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addGenericNatureResources($resources, $modulefilter, $options);
        }

        return $resources;
    }
}
    
/**
 * Initialize
 */
new PoP_ResourceLoader_NatureResourcesManager();

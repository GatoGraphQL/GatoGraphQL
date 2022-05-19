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

    function getHomeResources($componentFilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addHomeResources($resources, $componentFilter, $options);
        }

        return $resources;
    }
    
    function getAuthorResources($componentFilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addAuthorResources($resources, $componentFilter, $options);
        }

        return $resources;
    }
    
    function getTagResources($componentFilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addTagResources($resources, $componentFilter, $options);
        }

        return $resources;
    }       
    
    function get404Resources($componentFilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->add404Resources($resources, $componentFilter, $options);
        }

        return $resources;
    }

    function getSingleResources($componentFilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addSingleResources($resources, $componentFilter, $options);
        }

        return $resources;
    }
    
    function getPageResources($componentFilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addPageResources($resources, $componentFilter, $options);
        }

        return $resources;
    }
    
    function getGenericNatureResources($componentFilter, $options) {
    
        $resources = array();
        foreach ($this->processors as $processor) {

            $processor->addGenericNatureResources($resources, $componentFilter, $options);
        }

        return $resources;
    }
}
    
/**
 * Initialize
 */
new PoP_ResourceLoader_NatureResourcesManager();

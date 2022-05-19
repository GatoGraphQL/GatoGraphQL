<?php

class PoP_ResourceLoader_NatureResources_ProcessorBase implements PoP_ResourceLoader_NatureResources {

    public function __construct() {

        PoP_ResourceLoader_NatureResourcesManagerFactory::getInstance()->add($this);
    }

    // Get all the resources, for the different natures
    function addHomeResources(&$resources, $componentFilter, $options) {
    }
    
    function addAuthorResources(&$resources, $componentFilter, $options) {
    }
    
    function addTagResources(&$resources, $componentFilter, $options) {
    }       
    
    function add404Resources(&$resources, $componentFilter, $options) {
    }

    function addSingleResources(&$resources, $componentFilter, $options) {
    }
    
    function addPageResources(&$resources, $componentFilter, $options) {
    }
    
    function addGenericNatureResources(&$resources, $componentFilter, $options) {
    }
}

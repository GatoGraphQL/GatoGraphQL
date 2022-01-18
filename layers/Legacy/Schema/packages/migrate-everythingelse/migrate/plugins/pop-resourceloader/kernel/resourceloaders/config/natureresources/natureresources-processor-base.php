<?php

class PoP_ResourceLoader_NatureResources_ProcessorBase implements PoP_ResourceLoader_NatureResources {

    public function __construct() {

        PoP_ResourceLoader_NatureResourcesManagerFactory::getInstance()->add($this);
    }

    // Get all the resources, for the different natures
    function addHomeResources(&$resources, $modulefilter, $options) {
    }
    
    function addAuthorResources(&$resources, $modulefilter, $options) {
    }
    
    function addTagResources(&$resources, $modulefilter, $options) {
    }       
    
    function add404Resources(&$resources, $modulefilter, $options) {
    }

    function addSingleResources(&$resources, $modulefilter, $options) {
    }
    
    function addPageResources(&$resources, $modulefilter, $options) {
    }
    
    function addGenericNatureResources(&$resources, $modulefilter, $options) {
    }
}

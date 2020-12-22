<?php

interface PoP_ResourceLoader_NatureResources {

    function addHomeResources(&$resources, $modulefilter, $options);    
    function addAuthorResources(&$resources, $modulefilter, $options);    
    function addTagResources(&$resources, $modulefilter, $options);    
    function add404Resources(&$resources, $modulefilter, $options);
    function addSingleResources(&$resources, $modulefilter, $options);    
    function addPageResources(&$resources, $modulefilter, $options);
}

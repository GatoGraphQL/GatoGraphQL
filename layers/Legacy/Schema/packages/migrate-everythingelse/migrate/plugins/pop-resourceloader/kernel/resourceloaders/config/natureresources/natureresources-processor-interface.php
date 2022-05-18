<?php

interface PoP_ResourceLoader_NatureResources {

    function addHomeResources(&$resources, $componentFilter, $options);    
    function addAuthorResources(&$resources, $componentFilter, $options);    
    function addTagResources(&$resources, $componentFilter, $options);    
    function add404Resources(&$resources, $componentFilter, $options);
    function addSingleResources(&$resources, $componentFilter, $options);    
    function addPageResources(&$resources, $componentFilter, $options);
}

<?php

class PoP_ServiceWorkers_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor
{
    public const RESOURCE_SWREGISTRAR = 'sw-registrar';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_SWREGISTRAR],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_SWREGISTRAR => 'sw-registrar',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    // function getVersion(array $resource) {

    // // return POP_SERVICEWORKERS_VERSION;
    //     $vars = ApplicationState::getVars();
    //     return ApplicationInfoFacade::getInstance()->getVersion();
    // }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SWREGISTRAR:
                global $pop_serviceworkers_manager;
                return $pop_serviceworkers_manager->getDir();
        }
    
        return parent::getDir($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SWREGISTRAR:
                // This script file is dynamically generated getting data from all over the website, so its version depend on the website version
                return '.js';
        }
        return parent::getSuffix($resource);
    }
        
    // function extractMapping(array $resource) {

    //     // No need to extract the mapping from this file (also, it doesn't exist under that getDir() folder)
    //     switch ($resource[1]) {

    //         case self::RESOURCE_SWREGISTRAR:
                
    //             return false;
    //     }
    
    //     return parent::extractMapping($resource);
    // }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SWREGISTRAR:
                global $pop_serviceworkers_manager;
                return $pop_serviceworkers_manager->getFileurl('sw-registrar.js');
        }

        return parent::getFileUrl($resource);
    }
    
    // function getPath(array $resource) {

    //     $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
    //     return POP_SERVICEWORKERS_URL.'/js/'.$subpath.'libraries';
    // }

    // function canBundle(array $resource) {

    //     switch ($resource[1]) {

    //         case self::RESOURCE_SWREGISTRAR:
                
    //             return false;
    //     }
    
    //     return parent::canBundle($resource);
    // }
}



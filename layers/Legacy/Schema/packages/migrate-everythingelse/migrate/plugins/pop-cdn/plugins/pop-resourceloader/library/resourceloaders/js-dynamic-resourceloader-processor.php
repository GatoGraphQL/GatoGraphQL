<?php

class PoP_CDN_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor
{
    public const RESOURCE_CDNCONFIG = 'cdn-config';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CDNCONFIG],
        ];
    }
    
    public function getFilename(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CDNCONFIG:
                global $pop_cdn_configfile;
                return $pop_cdn_configfile->getFilename();
        }

        return parent::getFilename($resource);
    }
    
    // function getSuffix(array $resource) {
    
    //     switch ($resource[1]) {

    //         case self::RESOURCE_CDNCONFIG:
                
    //             // This script file is dynamically generated getting data from all over the website, so its version depend on the website version
    //             return '';
    //     }
    //     return parent::getSuffix($resource);
    // }
    
    // function getVersion(array $resource) {

    //     switch ($resource[1]) {

    //         case self::RESOURCE_CDNCONFIG:
                
    //             // This script file is dynamically generated getting data from all over the website, so its version depend on the website version
    //             return ApplicationInfoFacade::getInstance()->getVersion();
    //     }
    
    //     return POP_CDN_VERSION;
    // }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CDNCONFIG:
                global $pop_cdn_configfile;
                return $pop_cdn_configfile->getDir();
        }
    
        return parent::getDir($resource);
    }
        
    // function extractMapping(array $resource) {

    //     // No need to extract the mapping from this file (also, it doesn't exist under that getDir() folder)
    //     switch ($resource[1]) {

    //         case self::RESOURCE_CDNCONFIG:
                
    //             return false;
    //     }
    
    //     return parent::extractMapping($resource);
    // }
        
    // function canBundle(array $resource) {

    //     switch ($resource[1]) {

    //         case self::RESOURCE_CDNCONFIG:
                
    //             return false;
    //     }
    
    //     return parent::canBundle($resource);
    // }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CDNCONFIG:
                global $pop_cdn_configfile;
                return $pop_cdn_configfile->getFileurl();
        }

        return parent::getFileUrl($resource);
    }
    
    // function getPath(array $resource) {

    //     $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
    //     return POP_CDN_URL.'/js/'.$subpath.'libraries';
    // }

    // function getJsobjects(array $resource) {

    //     $objects = array(
    //         self::RESOURCE_CDNCONFIG => array(
    //             'CDNConfig',
    //         ),
    //     );
    //     if ($object = $objects[$resource[1]]) {

    //         return $object;
    //     }

    //     return parent::getJsobjects($resource);
    // }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_CDNCONFIG:
                $decorated[] = [PoP_CDN_JSResourceLoaderProcessor::class, PoP_CDN_JSResourceLoaderProcessor::RESOURCE_CDN];
                break;
        }

        return $decorated;
    }
}



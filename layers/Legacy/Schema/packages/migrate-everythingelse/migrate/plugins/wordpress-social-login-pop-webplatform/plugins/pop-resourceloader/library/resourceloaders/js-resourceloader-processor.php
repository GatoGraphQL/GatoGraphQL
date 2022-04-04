<?php

class WSL_PoPWebPlatform_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_WSLFUNCTIONS = 'wsl-functions';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_WSLFUNCTIONS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_WSLFUNCTIONS => 'wsl-functions',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return WSL_POPWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return WSL_POPWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return WSL_POPWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return WSL_POPWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }
        
    // function extractMapping(array $resource) {

    //     switch ($resource[1]) {

    //         case self::RESOURCE_WSLFUNCTIONS:

    //             return false;
    //     }
    
    //     return parent::extractMapping($resource);
    // }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_WSLFUNCTIONS:
                $decorated[] = [PoP_SocialLoginWebPlatform_ResourceLoaderProcessor::class, PoP_SocialLoginWebPlatform_ResourceLoaderProcessor::RESOURCE_SOCIALLOGINFUNCTIONS];
                break;
        }

        return $decorated;
    }
}



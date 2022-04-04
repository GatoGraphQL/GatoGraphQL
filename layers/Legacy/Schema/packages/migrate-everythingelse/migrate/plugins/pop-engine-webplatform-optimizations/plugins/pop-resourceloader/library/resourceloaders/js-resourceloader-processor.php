<?php

class PoP_FrontEndOptimization_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_INITIALIZEDATA = 'initializedata';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_INITIALIZEDATA],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_INITIALIZEDATA => 'initializedata',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_ENGINEWEBPLATFORMOPTIMIZATIONS_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ENGINEWEBPLATFORMOPTIMIZATIONS_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_ENGINEWEBPLATFORMOPTIMIZATIONS_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ENGINEWEBPLATFORMOPTIMIZATIONS_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_INITIALIZEDATA => array(
                'InitializeData',
            ),
        );

        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}



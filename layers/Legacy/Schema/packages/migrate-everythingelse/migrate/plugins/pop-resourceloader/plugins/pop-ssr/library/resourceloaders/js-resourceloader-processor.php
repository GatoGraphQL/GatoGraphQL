<?php

class PoP_SSR_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public const RESOURCE_SSR = 'ssr';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_SSR],
        ];
    }
        
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_SSR => 'ssr',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_SSR_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_SSR_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_SSR_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
        
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_SSR_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_SSR => array(
                'SSR',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}



<?php

class PoP_MultiDomain_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_MULTIDOMAIN = 'multidomain';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_MULTIDOMAIN],
        ];
    }
        
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_MULTIDOMAIN => 'multidomain',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_MULTIDOMAIN_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MULTIDOMAIN_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_MULTIDOMAIN_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
        
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MULTIDOMAIN_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_MULTIDOMAIN => array(
                'MultiDomain',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}



<?php

class PoP_MultiDomainSPAResourceLoader_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_MULTIDOMAINSPARESOURCELOADER = 'multidomain-resourceloader';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_MULTIDOMAINSPARESOURCELOADER],
        ];
    }
        
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_MULTIDOMAINSPARESOURCELOADER => 'multidomain-resourceloader',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_MULTIDOMAINSPARESOURCELOADER_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MULTIDOMAINSPARESOURCELOADER_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_MULTIDOMAINSPARESOURCELOADER_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
        
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MULTIDOMAINSPARESOURCELOADER_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_MULTIDOMAINSPARESOURCELOADER => array(
                'MultiDomainSPAResourceLoader',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}



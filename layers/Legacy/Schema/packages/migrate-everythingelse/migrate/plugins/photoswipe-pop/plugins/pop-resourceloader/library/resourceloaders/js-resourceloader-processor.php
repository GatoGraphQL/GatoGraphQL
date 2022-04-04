<?php

class PhotoSwipe_PoP_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_PHOTOSWIPE = 'photoswipe';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_PHOTOSWIPE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_PHOTOSWIPE => 'photoswipe-pop',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return PHOTOSWIPEPOP_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return PHOTOSWIPEPOP_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return PHOTOSWIPEPOP_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return PHOTOSWIPEPOP_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_PHOTOSWIPE => array(
                'PhotoSwipe',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_PHOTOSWIPE:
                $dependencies[] = [PhotoSwipe_PoP_VendorJSResourceLoaderProcessor::class, PhotoSwipe_PoP_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_PHOTOSWIPE];
                break;
        }

        return $dependencies;
    }
}



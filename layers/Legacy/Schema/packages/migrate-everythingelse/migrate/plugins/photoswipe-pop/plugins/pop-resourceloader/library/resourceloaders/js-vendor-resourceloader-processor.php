<?php

class PhotoSwipe_PoP_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_PHOTOSWIPE = 'external-photoswipe';
    public final const RESOURCE_EXTERNAL_PHOTOSWIPESKIN = 'external-photoswipe-skin';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_PHOTOSWIPE],
            [self::class, self::RESOURCE_EXTERNAL_PHOTOSWIPESKIN],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_PHOTOSWIPE => 'photoswipe',
            self::RESOURCE_EXTERNAL_PHOTOSWIPESKIN => 'photoswipe-ui-default',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return PHOTOSWIPEPOP_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return PHOTOSWIPEPOP_DIR.'/js/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_PHOTOSWIPE:
            case self::RESOURCE_EXTERNAL_PHOTOSWIPESKIN:
                return '.min.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_PHOTOSWIPE:
                case self::RESOURCE_EXTERNAL_PHOTOSWIPESKIN:
                    return 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
            }
        }

        return PHOTOSWIPEPOP_URL.'/js/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_PHOTOSWIPESKIN:
                $decorated[] = [self::class, self::RESOURCE_EXTERNAL_PHOTOSWIPE];
                break;
        }

        return $decorated;
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_PHOTOSWIPE:
                $dependencies[] = [PhotoSwipe_PoP_VendorCSSResourceLoaderProcessor::class, PhotoSwipe_PoP_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE];
                break;
        }

        return $dependencies;
    }
}



<?php

class PhotoSwipe_PoP_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_PHOTOSWIPE = 'css-external-photoswipe';
    public final const RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN = 'css-external-photoswipeskin';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE],
            [self::class, self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE => 'photoswipe',
            self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN => 'default-skin',
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
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE:
                return PHOTOSWIPEPOP_DIR.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;

            case self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN:
                return PHOTOSWIPEPOP_DIR.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin';
        }

        return parent::getDir($resource);
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE:
                case self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN:
                    return $this->getDir($resource).'/'.$this->getFilename($resource).$this->getSuffix($resource);
            }
        }

        return parent::getAssetPath($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $paths = array(
                self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE => 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION,
                self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN => 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin/',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE:
                return PHOTOSWIPEPOP_URL.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;

            case self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN:
                return PHOTOSWIPEPOP_URL.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin';
        }

        return parent::getPath($resource);
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPESKIN:
                $decorated[] = [self::class, self::RESOURCE_EXTERNAL_CSS_PHOTOSWIPE];
                break;
        }

        return $decorated;
    }
}



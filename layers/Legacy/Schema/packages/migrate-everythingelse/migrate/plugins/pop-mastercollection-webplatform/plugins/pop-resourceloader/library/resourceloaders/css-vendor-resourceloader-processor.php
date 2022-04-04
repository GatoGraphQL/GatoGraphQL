<?php

class PoP_CoreProcessors_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_PERFECTSCROLLBAR = 'css-external-perfectscrollbar';
    public final const RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT = 'css-external-dynamicmaxheight';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_PERFECTSCROLLBAR],
            [self::class, self::RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_PERFECTSCROLLBAR => 'perfect-scrollbar'.(!$use_cdn ? '.0.6.5' : ''),
            self::RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT => 'jquery.dynamicmaxheight',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }

    protected function alwaysMinified(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
                return false;
        }
    
        return parent::alwaysMinified($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
                $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
                return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/css/'.$subpath.'includes';
        }
    
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/css/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
                    return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/css/includes/'.$this->getFilename($resource).'.css';
            }
        
            $filenames = array(
                self::RESOURCE_EXTERNAL_CSS_PERFECTSCROLLBAR => 'perfect-scrollbar.0.6.5',
            );
            if ($filename = $filenames[$resource[1]]) {
                return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
            }
        }

        return parent::getAssetPath($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $paths = array(
                self::RESOURCE_EXTERNAL_CSS_PERFECTSCROLLBAR => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.5/css',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
                $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
                return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/css/'.$subpath.'includes';
        }

        return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/css/includes/cdn';
    }
}



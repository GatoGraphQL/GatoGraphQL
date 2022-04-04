<?php

class PoP_CoreProcessors_Bootstrap_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_DATERANGEPICKER = 'css-external-daterangepicker';
    public final const RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT = 'css-external-bootstrapmultiselect';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_DATERANGEPICKER],
            [self::class, self::RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_DATERANGEPICKER => 'daterangepicker'.(!$use_cdn ? '.2.1.24' : ''),
            self::RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT => 'bootstrap-multiselect.0.9.13',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }

    protected function alwaysMinified(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:
                return false;
        }
    
        return parent::alwaysMinified($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:
                $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
                return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/css/'.$subpath.'includes';
        }
    
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/css/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:
                    return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/css/includes/'.$this->getFilename($resource).'.css';
            }
        
            $filenames = array(
                self::RESOURCE_EXTERNAL_CSS_DATERANGEPICKER => 'daterangepicker.2.1.24',
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
                self::RESOURCE_EXTERNAL_CSS_DATERANGEPICKER => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:
                $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
                return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/css/'.$subpath.'includes';
        }

        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/css/includes/cdn';
    }
}



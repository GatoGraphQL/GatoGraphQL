<?php

class PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_BOOTSTRAP = 'external-bootstrap';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_BOOTSTRAP],
        ];
    }
    
    public function getHandle(array $resource)
    {
    
        // Other resources depend on bootstrap being called "bootstrap"
        $handles = array(
            self::RESOURCE_EXTERNAL_BOOTSTRAP => 'bootstrap',
        );
        if ($handle = $handles[$resource[1]]) {
            return $handle;
        }

        return parent::getHandle($resource);
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_BOOTSTRAP => 'bootstrap'.(!PoP_WebPlatform_ServerUtils::accessExternalcdnResources() ? '.3.3.7' : ''),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_BOOTSTRAP => 'bootstrap.3.3.7',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_BOOTSTRAP:
                return '.min.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_BOOTSTRAP:
                    return 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js';
            }
        }

        return POP_BOOTSTRAPWEBPLATFORM_URL.'/js/includes/cdn';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_BOOTSTRAP:
                $dependencies[] = [PoP_BootstrapWebPlatform_VendorCSSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_BOOTSTRAP];
                break;
        }

        return $dependencies;
    }
}



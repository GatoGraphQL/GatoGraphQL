<?php

class PoP_CoreProcessors_Bootstrap_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_DATERANGEPICKER = 'external-daterangepicker';
    public final const RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT = 'external-bootstrapmultiselect';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_DATERANGEPICKER],
            [self::class, self::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_DATERANGEPICKER => 'daterangepicker'.(!$use_cdn ? '.2.1.24' : ''),
            self::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT => 'bootstrap-multiselect'.(!$use_cdn ? '.0.9.13' : ''),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_DATERANGEPICKER => 'daterangepicker.2.1.24',
            self::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT => 'bootstrap-multiselect.0.9.13',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_DATERANGEPICKER:
            case self::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT:
                return '.min.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $paths = array(
                self::RESOURCE_EXTERNAL_DATERANGEPICKER => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24',
                self::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/js/includes/cdn';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT:
            case self::RESOURCE_EXTERNAL_DATERANGEPICKER:
                $dependencies[] = [PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAP];
                break;
        }

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_DATERANGEPICKER:
                $dependencies[] = [PoP_BaseCollectionWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_MOMENT];
                break;

            case self::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT:
                $dependencies[] = [PoP_CoreProcessors_Bootstrap_VendorCSSResourceLoaderProcessor::class, PoP_CoreProcessors_Bootstrap_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_BOOTSTRAPMULTISELECT];
                break;
        }

        return $dependencies;
    }
}



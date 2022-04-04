<?php

class PoP_AddLocations_Bootstrap_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_BOOTSTRAPCREATELOCATION = 'em-bootstrap-create-location';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BOOTSTRAPCREATELOCATION],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_BOOTSTRAPCREATELOCATION => 'bootstrap-create-location',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_ADDLOCATIONSWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ADDLOCATIONSWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/bootstrap';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_ADDLOCATIONSWEBPLATFORM_DIR.'/js/libraries/bootstrap/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ADDLOCATIONSWEBPLATFORM_URL.'/js/'.$subpath.'libraries/bootstrap';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_BOOTSTRAPCREATELOCATION => array(
                'BootstrapCreateLocation',
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
            case self::RESOURCE_BOOTSTRAPCREATELOCATION:
                $dependencies[] = [PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAP];
                break;
        }

        return $dependencies;
    }
}



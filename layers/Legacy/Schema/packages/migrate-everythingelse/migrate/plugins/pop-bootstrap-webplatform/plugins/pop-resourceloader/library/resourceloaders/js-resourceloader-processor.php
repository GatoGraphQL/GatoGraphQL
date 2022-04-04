<?php

class PoP_BootstrapWebPlatform_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_BOOTSTRAP = 'bootstrap';
    public final const RESOURCE_CUSTOMBOOTSTRAP = 'custombootstrap';
    public final const RESOURCE_MODALS = 'modals';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BOOTSTRAP],
            [self::class, self::RESOURCE_CUSTOMBOOTSTRAP],
            [self::class, self::RESOURCE_MODALS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_BOOTSTRAP => 'bootstrap',
            self::RESOURCE_CUSTOMBOOTSTRAP => 'custombootstrap',
            self::RESOURCE_MODALS => 'modals',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_BOOTSTRAPWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_BOOTSTRAPWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_BOOTSTRAP => array(
                'Bootstrap',
            ),
            self::RESOURCE_CUSTOMBOOTSTRAP => array(
                'CustomBootstrap',
            ),
            self::RESOURCE_MODALS => array(
                'Modals',
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
            case self::RESOURCE_BOOTSTRAP:
            case self::RESOURCE_CUSTOMBOOTSTRAP:
                $dependencies[] = [PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAP];
                break;
        }

        return $dependencies;
    }
}



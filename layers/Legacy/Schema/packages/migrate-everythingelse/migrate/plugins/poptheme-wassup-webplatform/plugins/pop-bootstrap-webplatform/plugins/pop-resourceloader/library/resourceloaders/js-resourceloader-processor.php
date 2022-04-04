<?php

class PoPTheme_Wassup_Bootstrap_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_BOOTSTRAPCUSTOMFUNCTIONS = 'bootstrap-custom-functions';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BOOTSTRAPCUSTOMFUNCTIONS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_BOOTSTRAPCUSTOMFUNCTIONS => 'bootstrap-custom-functions',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/bootstrap';
    }
    
    public function getAssetPath(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/js/libraries/bootstrap/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POPTHEME_WASSUPWEBPLATFORM_URL.'/js/'.$subpath.'libraries/bootstrap';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_BOOTSTRAPCUSTOMFUNCTIONS => array(
                'BootstrapCustomFunctions',
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
            case self::RESOURCE_BOOTSTRAPCUSTOMFUNCTIONS:
                // Because it calls Bootstrap function "alert", we must make sure Bootstrap is loaded or it produces a JS error
                // (this happens when the internal/external method call mapping has not been generated)
                $dependencies[] = [PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAP];
                break;
        }

        return $dependencies;
    }
}



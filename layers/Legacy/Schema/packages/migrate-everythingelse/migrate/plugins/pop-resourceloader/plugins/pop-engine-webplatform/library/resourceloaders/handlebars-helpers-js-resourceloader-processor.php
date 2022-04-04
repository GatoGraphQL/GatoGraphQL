<?php

class PoP_FrontEnd_HandlebarsHelpersJSResourceLoaderProcessor extends PoP_HandlebarsHelpersJSResourceLoaderProcessor
{
    public final const RESOURCE_HANDLEBARSHELPERS_KERNEL = 'handlebars-helpers-kernel';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_KERNEL],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_HANDLEBARSHELPERS_KERNEL => 'kernel',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ENGINEWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/handlebars-helpers';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_DIR.'/js/libraries/handlebars-helpers/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ENGINEWEBPLATFORM_URL.'/js/'.$subpath.'libraries/handlebars-helpers';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_HANDLEBARSHELPERS_KERNEL:
                $dependencies[] = [PoP_FrontEnd_VendorJSResourceLoaderProcessor::class, PoP_FrontEnd_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_HANDLEBARS];
                break;
        }

        return $dependencies;
    }
}



<?php

class PoP_FrontEnd_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_HANDLEBARS = 'external-handlebars';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_HANDLEBARS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_HANDLEBARS => 'handlebars.runtime'.(!PoP_WebPlatform_ServerUtils::accessExternalcdnResources() ? '.4.0.10' : ''),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_RESOURCELOADER_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_HANDLEBARS:
                return $this->getDir($resource).'/handlebars.runtime.4.0.10'.$this->getSuffix($resource);
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_HANDLEBARS:
                return '.min.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_HANDLEBARS:
                    return 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.10';
            }
        }

        return POP_ENGINEWEBPLATFORM_URL.'/js/includes/cdn';
    }
}



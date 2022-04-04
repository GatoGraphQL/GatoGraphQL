<?php

class PoP_Events_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_FULLCALENDAR = 'external-fullcalendar';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_FULLCALENDAR],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_FULLCALENDAR => 'fullcalendar'.(!PoP_WebPlatform_ServerUtils::accessExternalcdnResources() ? '.3.8.2' : ''),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_EVENTSWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POP_EVENTSWEBPLATFORM_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_FULLCALENDAR => 'fullcalendar.3.8.2',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_FULLCALENDAR:
                return '.min.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_FULLCALENDAR:
                    return 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2';
            }
        }

        return POP_EVENTSWEBPLATFORM_URL.'/js/includes/cdn';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_FULLCALENDAR:
                $dependencies[] = [PoP_BaseCollectionWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_MOMENT];
                $dependencies[] = [PoP_Events_VendorCSSResourceLoaderProcessor::class, PoP_Events_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_FULLCALENDAR];
                break;
        }

        return $dependencies;
    }
}



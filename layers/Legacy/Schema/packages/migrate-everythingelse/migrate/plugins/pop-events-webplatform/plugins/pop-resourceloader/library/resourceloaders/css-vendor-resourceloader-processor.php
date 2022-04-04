<?php

class PoP_Events_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_FULLCALENDAR = 'css-external-fullcalendar';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_FULLCALENDAR],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_FULLCALENDAR => 'fullcalendar'.(!$use_cdn ? '.3.8.2' : ''),
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
        return POP_EVENTSWEBPLATFORM_DIR.'/css/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $filenames = array(
                self::RESOURCE_EXTERNAL_CSS_FULLCALENDAR => 'fullcalendar.3.8.2',
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
                self::RESOURCE_EXTERNAL_CSS_FULLCALENDAR => 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        return POP_EVENTSWEBPLATFORM_URL.'/css/includes/cdn';
    }
}



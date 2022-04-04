<?php

class EM_PoPProcessors_QTX_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_FULLCALENDARLOCALE = 'external-fullcalendar-locale';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE => getEmQtransxFullcalendarLocaleFilename(),
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
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE:
                return POP_EVENTSWEBPLATFORM_DIR.'/js/includes/cdn/fullcalendar.3.8.2-lang';
        }
    
        return POP_EVENTSWEBPLATFORM_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE => getEmQtransxFullcalendarLocaleFilename(),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE:
                return '.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE:
                    return 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/lang';
                ;
            }
        }

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE:
                return POP_EVENTSWEBPLATFORM_URL.'/js/includes/cdn/fullcalendar.3.8.2-lang';
        }

        return POP_EVENTSWEBPLATFORM_URL.'/js/includes/cdn';
    }
    
    // function canBundle(array $resource) {

    //     switch ($resource[1]) {
            
    //         case self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE:

    //             return false;
    //     }

    //     return parent::canBundle($resource);
    // }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_FULLCALENDARLOCALE:
                // // Add Locale file, if applicable
                // if (getEmQtransxFullcalendarLocaleFilename()) {

                $decorated[] = [PoP_Events_VendorJSResourceLoaderProcessor::class, PoP_Events_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FULLCALENDAR];
                // }
                break;
        }

        return $decorated;
    }
}



<?php

class PoP_Events_CalendarResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_FULLCALENDAR = 'em-fullcalendar';
    public final const RESOURCE_FULLCALENDARMEMORY = 'em-fullcalendar-memory';
    public final const RESOURCE_FULLCALENDARADDEVENTS = 'em-fullcalendar-addevents';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_FULLCALENDAR],
            [self::class, self::RESOURCE_FULLCALENDARMEMORY],
            [self::class, self::RESOURCE_FULLCALENDARADDEVENTS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_FULLCALENDAR => 'fullcalendar',
            self::RESOURCE_FULLCALENDARMEMORY => 'fullcalendar-memory',
            self::RESOURCE_FULLCALENDARADDEVENTS => 'fullcalendar-addevents',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_EVENTSWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_EVENTSWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/3rdparties/calendar';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_EVENTSWEBPLATFORM_DIR.'/js/libraries/3rdparties/calendar/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_EVENTSWEBPLATFORM_URL.'/js/'.$subpath.'libraries/3rdparties/calendar';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_FULLCALENDAR => array(
                'FullCalendar',
                'FullCalendarControls',
            ),
            self::RESOURCE_FULLCALENDARMEMORY => array(
                'FullCalendarMemory',
            ),
            self::RESOURCE_FULLCALENDARADDEVENTS => array(
                'FullCalendarAddEvents',
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
            case self::RESOURCE_FULLCALENDAR:
                $dependencies[] = [PoP_Events_VendorJSResourceLoaderProcessor::class, PoP_Events_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FULLCALENDAR];
                break;
        }

        return $dependencies;
    }
}



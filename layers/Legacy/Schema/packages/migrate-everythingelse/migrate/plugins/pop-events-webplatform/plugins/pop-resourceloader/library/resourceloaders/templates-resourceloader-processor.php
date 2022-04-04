<?php

class PoP_Events_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_CALENDAR = 'calendar';
    public final const RESOURCE_CALENDAR_INNER = 'calendar_inner';
    public final const RESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE = 'layout_carousel_indicators_eventdate';
    public final const RESOURCE_LAYOUT_DATETIME = 'layout_datetime';
    public final const RESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER = 'layoutcalendar_content_popover';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CALENDAR],
            [self::class, self::RESOURCE_CALENDAR_INNER],
            [self::class, self::RESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE],
            [self::class, self::RESOURCE_LAYOUT_DATETIME],
            [self::class, self::RESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_CALENDAR => POP_TEMPLATE_CALENDAR,
            self::RESOURCE_CALENDAR_INNER => POP_TEMPLATE_CALENDAR_INNER,
            self::RESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE => POP_TEMPLATE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE,
            self::RESOURCE_LAYOUT_DATETIME => POP_TEMPLATE_LAYOUT_DATETIME,
            self::RESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER => POP_TEMPLATE_LAYOUTCALENDAR_CONTENT_POPOVER,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_EVENTSWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_EVENTSWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_EVENTSWEBPLATFORM_DIR.'/js/dist/templates';
    }
    
    public function getGlobalscopeMethodCalls(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CALENDAR_INNER:
                return array(
                    'FullCalendarAddEvents' => array(
                        'addEvents',
                    ),
                    'Manager' => array(
                        'getBlock',
                        'getPageSection',
                    ),
                );
        }

        return parent::getGlobalscopeMethodCalls($resource);
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_CALENDAR_INNER:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_DBOBJECT];
                break;
        }

        return $dependencies;
    }
}



<?php

class PoP_Events_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_AUTHOREVENTS = 'filter-authorevents';
    public const MODULE_FILTER_TAGEVENTS = 'filter-tagevents';
    public const MODULE_FILTER_AUTHOREVENTSCALENDAR = 'filter-authoreventscalendar';
    public const MODULE_FILTER_TAGEVENTSCALENDAR = 'filter-tageventscalendar';
    public const MODULE_FILTER_EVENTS = 'filter-events';
    public const MODULE_FILTER_EVENTSCALENDAR = 'filter-eventscalendar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_EVENTS],
            [self::class, self::MODULE_FILTER_AUTHOREVENTS],
            [self::class, self::MODULE_FILTER_TAGEVENTS],
            [self::class, self::MODULE_FILTER_EVENTSCALENDAR],
            [self::class, self::MODULE_FILTER_AUTHOREVENTSCALENDAR],
            [self::class, self::MODULE_FILTER_TAGEVENTSCALENDAR],
        );
    }
    
    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_EVENTS => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_EVENTS],
            self::MODULE_FILTER_AUTHOREVENTS => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHOREVENTS],
            self::MODULE_FILTER_TAGEVENTS => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_TAGEVENTS],
            self::MODULE_FILTER_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_EVENTSCALENDAR],
            self::MODULE_FILTER_AUTHOREVENTSCALENDAR => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHOREVENTSCALENDAR],
            self::MODULE_FILTER_TAGEVENTSCALENDAR => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_TAGEVENTSCALENDAR],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }
    
        return parent::getInnerSubmodule($module);
    }
}




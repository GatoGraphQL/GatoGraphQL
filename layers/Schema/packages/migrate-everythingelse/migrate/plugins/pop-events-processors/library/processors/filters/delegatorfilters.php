<?php

class PoP_Events_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public const MODULE_DELEGATORFILTER_AUTHOREVENTS = 'delegatorfilter-authorevents';
    public const MODULE_DELEGATORFILTER_TAGEVENTS = 'delegatorfilter-tagevents';
    public const MODULE_DELEGATORFILTER_AUTHOREVENTSCALENDAR = 'delegatorfilter-authoreventscalendar';
    public const MODULE_DELEGATORFILTER_TAGEVENTSCALENDAR = 'delegatorfilter-tageventscalendar';
    public const MODULE_DELEGATORFILTER_EVENTS = 'delegatorfilter-events';
    public const MODULE_DELEGATORFILTER_EVENTSCALENDAR = 'delegatorfilter-eventscalendar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_EVENTS],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHOREVENTS],
            [self::class, self::MODULE_DELEGATORFILTER_TAGEVENTS],
            [self::class, self::MODULE_DELEGATORFILTER_EVENTSCALENDAR],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHOREVENTSCALENDAR],
            [self::class, self::MODULE_DELEGATORFILTER_TAGEVENTSCALENDAR],
        );
    }
    
    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_EVENTS => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_EVENTS],
            self::MODULE_DELEGATORFILTER_AUTHOREVENTS => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHOREVENTS],
            self::MODULE_DELEGATORFILTER_TAGEVENTS => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGEVENTS],
            self::MODULE_DELEGATORFILTER_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_EVENTSCALENDAR],
            self::MODULE_DELEGATORFILTER_AUTHOREVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR],
            self::MODULE_DELEGATORFILTER_TAGEVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGEVENTSCALENDAR],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }
    
        return parent::getInnerSubmodule($module);
    }
}




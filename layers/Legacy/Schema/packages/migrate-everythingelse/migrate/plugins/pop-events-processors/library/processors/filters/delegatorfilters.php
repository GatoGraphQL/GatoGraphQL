<?php

class PoP_Events_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_AUTHOREVENTS = 'delegatorfilter-authorevents';
    public final const MODULE_DELEGATORFILTER_TAGEVENTS = 'delegatorfilter-tagevents';
    public final const MODULE_DELEGATORFILTER_AUTHOREVENTSCALENDAR = 'delegatorfilter-authoreventscalendar';
    public final const MODULE_DELEGATORFILTER_TAGEVENTSCALENDAR = 'delegatorfilter-tageventscalendar';
    public final const MODULE_DELEGATORFILTER_EVENTS = 'delegatorfilter-events';
    public final const MODULE_DELEGATORFILTER_EVENTSCALENDAR = 'delegatorfilter-eventscalendar';

    public function getComponentVariationsToProcess(): array
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

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_EVENTS => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTS],
            self::MODULE_DELEGATORFILTER_AUTHOREVENTS => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTS],
            self::MODULE_DELEGATORFILTER_TAGEVENTS => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTS],
            self::MODULE_DELEGATORFILTER_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTSCALENDAR],
            self::MODULE_DELEGATORFILTER_AUTHOREVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR],
            self::MODULE_DELEGATORFILTER_TAGEVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSimpleFilterInners::class, PoP_Events_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTSCALENDAR],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}




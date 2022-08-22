<?php

class PoP_Events_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_AUTHOREVENTS = 'filter-authorevents';
    public final const COMPONENT_FILTER_TAGEVENTS = 'filter-tagevents';
    public final const COMPONENT_FILTER_AUTHOREVENTSCALENDAR = 'filter-authoreventscalendar';
    public final const COMPONENT_FILTER_TAGEVENTSCALENDAR = 'filter-tageventscalendar';
    public final const COMPONENT_FILTER_EVENTS = 'filter-events';
    public final const COMPONENT_FILTER_EVENTSCALENDAR = 'filter-eventscalendar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTER_EVENTS,
            self::COMPONENT_FILTER_AUTHOREVENTS,
            self::COMPONENT_FILTER_TAGEVENTS,
            self::COMPONENT_FILTER_EVENTSCALENDAR,
            self::COMPONENT_FILTER_AUTHOREVENTSCALENDAR,
            self::COMPONENT_FILTER_TAGEVENTSCALENDAR,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_EVENTS => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_EVENTS],
            self::COMPONENT_FILTER_AUTHOREVENTS => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTS],
            self::COMPONENT_FILTER_TAGEVENTS => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTS],
            self::COMPONENT_FILTER_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_EVENTSCALENDAR],
            self::COMPONENT_FILTER_AUTHOREVENTSCALENDAR => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR],
            self::COMPONENT_FILTER_TAGEVENTSCALENDAR => [PoP_Events_Module_Processor_CustomFilterInners::class, PoP_Events_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}




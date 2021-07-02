<?php

trait PoP_Events_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_EVENTS_ROUTE_EVENTS,
                POP_EVENTS_ROUTE_EVENTSCALENDAR,
                POP_EVENTS_ROUTE_PASTEVENTS,
            )
        );
    }
}

<?php

trait PoP_Locations_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONS_ROUTE_LOCATIONS,
                POP_LOCATIONS_ROUTE_LOCATIONSMAP,
            )
        );
    }
}

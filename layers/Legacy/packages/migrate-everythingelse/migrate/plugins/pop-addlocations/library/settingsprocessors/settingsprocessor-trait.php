<?php

trait PoP_AddLocations_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
            )
        );
    }
}

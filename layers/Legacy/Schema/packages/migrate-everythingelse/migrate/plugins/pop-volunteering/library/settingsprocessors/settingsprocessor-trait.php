<?php

trait PoP_Volunteering_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_VOLUNTEERING_ROUTE_VOLUNTEER,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_VOLUNTEERING_ROUTE_VOLUNTEER => true,
        );
    }
}

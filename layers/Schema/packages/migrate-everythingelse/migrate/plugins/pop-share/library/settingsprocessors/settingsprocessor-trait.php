<?php

trait PoP_Share_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SHARE_ROUTE_SHAREBYEMAIL,
            )
        );
    }

    public function isFunctional()
    {
        return array(
            POP_SHARE_ROUTE_SHAREBYEMAIL => true,
        );
    }
}

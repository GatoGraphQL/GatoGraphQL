<?php

trait PoP_ContactUs_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_CONTACTUS_ROUTE_CONTACTUS,
            )
        );
    }
}

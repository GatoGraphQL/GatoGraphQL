<?php

trait PoP_Newsletter_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_NEWSLETTER_ROUTE_NEWSLETTER,
                POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION,
            )
        );
    }
}

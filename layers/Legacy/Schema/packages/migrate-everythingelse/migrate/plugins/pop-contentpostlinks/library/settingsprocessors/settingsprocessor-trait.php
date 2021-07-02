<?php

trait PoP_ContentPostLinks_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            )
        );
    }
}

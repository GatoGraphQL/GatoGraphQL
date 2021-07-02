<?php

trait PoP_LocationPosts_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            )
        );
    }
}

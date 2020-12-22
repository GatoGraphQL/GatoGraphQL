<?php
namespace PoPSchema\Users;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERS_ROUTE_USERS,
            )
        );
    }
}

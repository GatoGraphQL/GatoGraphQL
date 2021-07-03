<?php
namespace PoPSchema\Users;

use PoPSchema\Users\ComponentConfiguration as UsersComponentConfiguration;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                UsersComponentConfiguration::getUsersRoute(),
            )
        );
    }
}

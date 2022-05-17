<?php
namespace PoPCMSSchema\Users;

use PoPCMSSchema\Users\ModuleConfiguration as UsersComponentConfiguration;

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

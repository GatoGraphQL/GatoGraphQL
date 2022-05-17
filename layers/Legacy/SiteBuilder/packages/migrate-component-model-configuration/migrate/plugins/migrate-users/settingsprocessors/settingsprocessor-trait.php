<?php
namespace PoPCMSSchema\Users;

use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                UsersModuleConfiguration::getUsersRoute(),
            )
        );
    }
}

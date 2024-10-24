<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\Registries\SystemSettingsCategoryRegistryFacade;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class AdminHelpers
{
    public static function getSettingsPageTabURL(string $module): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $settingsCategoryRegistry = SystemSettingsCategoryRegistryFacade::getInstance();
        /**
         * @var SettingsMenuPage
         */
        $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $settingsCategory = $moduleResolver->getSettingsCategory($module);
        return \admin_url(sprintf(
            'admin.php?page=%s&%s=%s&%s=%s',
            $settingsMenuPage->getScreenID(),
            RequestParams::CATEGORY,
            $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory)->getID($settingsCategory),
            RequestParams::MODULE,
            $moduleResolver->getID($module)
        ));
    }
}

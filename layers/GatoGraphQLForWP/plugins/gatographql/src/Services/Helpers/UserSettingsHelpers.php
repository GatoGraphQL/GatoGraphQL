<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

class UserSettingsHelpers
{
    use BasicServiceTrait;

    private ?UserSettingsManagerInterface $userSettingsManager = null;

    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }

    /**
     * Return the default Settings for custom post IDs
     * for some module
     *
     * @return int[]
     */
    public function getUserDefaultSettingCustomPostValueIDs(string $module): array
    {
        /** @var int[] */
        return $this->getUserSettingsManager()->getSetting(
            $module,
            ModuleSettingOptions::DEFAULT_VALUE
        );
    }
}

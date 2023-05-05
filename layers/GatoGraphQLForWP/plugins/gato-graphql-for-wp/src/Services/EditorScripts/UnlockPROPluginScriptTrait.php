<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EditorScripts;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use PoP\ComponentModel\App;

trait UnlockPROPluginScriptTrait
{
    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getUnlockPROPluginLocalizedData(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return [
            'displayUnlockPROPluginMessage' => $moduleConfiguration->displayPROPluginInformationInMainPlugin(),
            'proPluginWebsiteURL' => $moduleConfiguration->getPROPluginWebsiteURL(),
        ];
    }
}

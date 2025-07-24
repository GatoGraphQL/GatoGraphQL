<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractSettingsMenuPage;

abstract class AbstractExecuteActionWithCustomSettingsMenuPage extends AbstractSettingsMenuPage
{
    /**
     * Get the settings items which have this target
     *
     * @return array<array<string,mixed>>
     */
    protected function getSettingsItems(): array
    {
        $upstreamSettingsItems = parent::getSettingsItems();
        $settingsItems = [];
        foreach ($upstreamSettingsItems as $settingItem) {
            $settingItem['settings'] = array_values(array_filter(
                $settingItem['settings'] ?? [],
                fn (array $item) => array_intersect(
                    $this->getPossibleTargets(),
                    $item[Properties::ADDITIONAL_TARGETS] ?? []
                ) !== []
            ));
            if ($settingItem['settings'] === []) {
                continue;
            }
            $settingsItems[] = $settingItem;
        }
        return $settingsItems;
    }

    /**
    * @return string[]
    */
    abstract protected function getPossibleTargets(): array;
}

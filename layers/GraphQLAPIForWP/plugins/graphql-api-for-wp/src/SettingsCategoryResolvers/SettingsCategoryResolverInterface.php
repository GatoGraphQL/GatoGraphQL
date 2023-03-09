<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers;

interface SettingsCategoryResolverInterface
{
    /**
     * @return string[]
     */
    public function getSettingsCategoriesToResolve(): array;

    public function getDescription(string $settingsCategory): ?string;

    public function getDBOptionName(string $settingsCategory): string;

    public function getOptionsFormName(string $settingsCategory): string;
}

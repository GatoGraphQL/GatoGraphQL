<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers;

interface SettingsCategoryResolverInterface
{
    /**
     * @return string[]
     */
    public function getSettingsCategoriesToResolve(): array;

    public function getID(string $settingsCategory): string;

    public function getName(string $settingsCategory): string;

    public function getDBOptionName(string $settingsCategory): string;

    public function getOptionsFormName(string $settingsCategory): string;

    /**
     * When printing the Settings, not all categories
     * need to submit a form. In particular,
     * "Plugin Management" does not.
     */
    public function addOptionsFormSubmitButton(string $settingsCategory): bool;
}

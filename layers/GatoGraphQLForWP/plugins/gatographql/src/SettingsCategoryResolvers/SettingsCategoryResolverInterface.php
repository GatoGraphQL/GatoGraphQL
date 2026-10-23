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

    public function getPriority(string $settingsCategory): int;

    /**
     * When printing the Settings, not all categories
     * need to submit a form. In particular,
     * "Plugin Management" does not.
     */
    public function addOptionsFormSubmitButton(string $settingsCategory): bool;

    /**
     * Whether the changes in this category can be saved together
     * with those of the other categories, via "Save all changes".
     * Those that can't keep their own submit button.
     */
    public function canSaveWithOtherCategories(string $settingsCategory): bool;

    /**
     * What applies to all the sections of the category, printed once
     * above them: a title, a line, and the help behind its "i" button
     *
     * @return array{title:string,description:string,help?:string}|null
     */
    public function getIntro(string $settingsCategory): ?array;
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

interface ModuleResolverInterface
{
    /**
     * @return string[]
     */
    public function getModulesToResolve(): array;
    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int;
    /**
     * This is a list of lists of modules, as to model both OR and AND conditions
     * The innermost list is an OR: if any module is enabled, then the condition succeeds
     * The outermost list is an AND: all list must succeed for this module to be enabled
     * Eg: the Schema Configuration is enabled if either the Custom Endpoints or
     * the Persisted Query are enabled:
     * [
     *   [self::PUBLIC_PERSISTED_QUERIES, self::CUSTOM_ENDPOINTS],
     * ]
     */
    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array;
    /**
     * Indicates if a module has all requirements satisfied (such as version of WordPress) to be enabled
     */
    public function areRequirementsSatisfied(string $module): bool;
    /**
     * Is the module's state (enabled/disable) fixed?
     *
     * @return boolean|null `true` as enabled, `false` as disabled, `null` as it has no predefined state
     */
    public function isPredefinedEnabledOrDisabled(string $module): ?bool;
    public function isHidden(string $module): bool;
    public function areSettingsHidden(string $module): bool;
    public function getID(string $module): string;
    public function getName(string $module): string;
    public function getDescription(string $module): string;
    public function hasSettings(string $module): bool;
    /**
     * The type of the module
     */
    public function getModuleType(string $module): string;
    /**
     * Array with the inputs to show as settings for the module:
     * - name
     * - type (string, bool, int)
     * - possible values
     * - is multiple
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array;
    /**
     * The category where to display the settings:
     *
     * - GraphQL API Settings
     * - Plugin Settings
     * - Plugin Management
     */
    public function getSettingsCategory(string $module): string;
    /**
     * Default value for an option set by the module
     */
    public function getSettingOptionName(string $module, string $option): string;
    /**
     * Indicate if the given value is valid for that option
     */
    public function isValidValue(string $module, string $option, mixed $value): bool;
    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed;
    public function isEnabledByDefault(string $module): bool;
    // public function getURL(string $module): ?string;
    public function getSlug(string $module): string;
    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool;
    /**
     * HTML Documentation for the module
     */
    public function getDocumentation(string $module): ?string;
}

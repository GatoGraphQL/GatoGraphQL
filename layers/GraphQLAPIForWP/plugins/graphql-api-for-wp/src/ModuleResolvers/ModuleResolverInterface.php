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
     *   [self::PERSISTED_QUERIES, self::CUSTOM_ENDPOINTS],
     * ]
     */
    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array;
    /**
     * Indicates if a module has all requirements satisfied (such as version of WordPress) to be enabled
     *
     * @param string $module
     * @return boolean
     */
    public function areRequirementsSatisfied(string $module): bool;
    /**
     * Can the module be disabled by the user?
     *
     * @param string $module
     * @return boolean
     */
    public function canBeDisabled(string $module): bool;
    public function isHidden(string $module): bool;
    public function getID(string $module): string;
    public function getName(string $module): string;
    public function getDescription(string $module): string;
    public function hasSettings(string $module): bool;
    /**
     * The type of the module
     *
     * @param string $module
     * @return string
     */
    public function getModuleType(string $module): string;
    /**
     * Array with the inputs to show as settings for the module:
     * - name
     * - type (string, bool, int)
     * - possible values
     * - is multiple
     *
     * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array;
    /**
     * Default value for an option set by the module
     */
    public function getSettingOptionName(string $module, string $option): string;
    /**
     * Indicate if the given value is valid for that option
     *
     * @param string $module
     * @param string $option
     * @param mixed $value
     * @return bool
     */
    public function isValidValue(string $module, string $option, $value): bool;
    /**
     * Name of the setting item, to store in the DB
     *
     * @param string $module
     * @param string $option
     * @return mixed
     */
    public function getSettingsDefaultValue(string $module, string $option);
    public function isEnabledByDefault(string $module): bool;
    // public function getURL(string $module): ?string;
    public function getSlug(string $module): string;
    /**
     * Does the module have HTML Documentation?
     *
     * @param string $module
     * @return bool
     */
    public function hasDocumentation(string $module): bool;
    /**
     * HTML Documentation for the module
     *
     * @param string $module
     * @return string|null
     */
    public function getDocumentation(string $module): ?string;
}

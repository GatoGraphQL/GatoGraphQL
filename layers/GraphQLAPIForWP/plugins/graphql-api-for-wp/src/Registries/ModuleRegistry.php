<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\ModuleNotExistsException;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;

class ModuleRegistry implements ModuleRegistryInterface
{
    private ?UserSettingsManagerInterface $userSettingsManager = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }

    /**
     * @var ModuleResolverInterface[]
     */
    protected array $moduleResolvers = [];

    /**
     * @var array<string,ModuleResolverInterface>
     */
    protected array $modulesResolversByModuleAndPriority = [];

    /**
     * @var array<string,ModuleResolverInterface>
     */
    protected array $moduleResolversByModule = [];

    public function addModuleResolver(ModuleResolverInterface $moduleResolver): void
    {
        $this->moduleResolvers[] = $moduleResolver;
        foreach ($moduleResolver->getModulesToResolve() as $module) {
            $this->moduleResolversByModule[$module] = $moduleResolver;
        }
    }
    /**
     * Order the moduleResolvers by priority
     * @return array<string,ModuleResolverInterface>
     */
    protected function getModuleResolversByModuleAndPriority(): array
    {
        if (empty($this->modulesResolversByModuleAndPriority)) {
            $moduleResolvers = $this->moduleResolvers;
            uasort($moduleResolvers, function (ModuleResolverInterface $a, ModuleResolverInterface $b): int {
                return $b->getPriority() <=> $a->getPriority();
            });
            foreach ($moduleResolvers as $moduleResolver) {
                foreach ($moduleResolver->getModulesToResolve() as $module) {
                    $this->modulesResolversByModuleAndPriority[$module] = $moduleResolver;
                }
            }
        }
        return $this->modulesResolversByModuleAndPriority;
    }

    /**
     * @return string[]
     */
    public function getAllModules(
        bool $onlyEnabled = false,
        bool $onlyHasSettings = false,
        bool $onlyVisible = false,
        bool $onlyWithVisibleSettings = false,
        ?string $settingsCategory = null,
    ): array {
        $modules = array_keys($this->getModuleResolversByModuleAndPriority());
        /**
         * Important: first filter by $settingsCategory, and only
         * then by $onlyEnabled!
         *
         * This is to avoid an endless loop:
         *
         * - Module SINGLE_ENDPOINT calls
         *   `BehaviorHelpers::areUnsafeDefaultsEnabled()` in
         *   `isEnabledByDefault`
         * - `BehaviorHelpers::areUnsafeDefaultsEnabled()` calls
         *   `->normalizeSettingsByCategory` which loads all modules with
         *   `->getAllModules(`, and SINGLE_ENDPOINT is one of them,
         *   but it would call again `areUnsafeDefaultsEnabled`
         *   to decide if it's enabled or not...
         *
         * Because this issue will happen when execute Reset Settings,
         * which is where the "safe"/"unsafe" default behavior is changed,
         * and as SINGLE_ENDPOINT is on a different settingsCategory,
         * then this problem is avoided by first filtering by settingsCategory,
         * so that SINGLE_ENDPOINT will not be retrieved in `getAllModules`.
         */
        if ($settingsCategory !== null) {
            $modules = array_filter(
                $modules,
                fn (string $module) => $this->getModuleResolver($module)->getSettingsCategory($module) === $settingsCategory
            );
        }
        if ($onlyEnabled) {
            $modules = array_filter(
                $modules,
                fn (string $module) => $this->isModuleEnabled($module)
            );
        }
        if ($onlyHasSettings) {
            $modules = array_filter(
                $modules,
                fn (string $module) => $this->getModuleResolver($module)->hasSettings($module)
            );
        }
        if ($onlyVisible) {
            $modules = array_filter(
                $modules,
                fn (string $module) => !$this->getModuleResolver($module)->isHidden($module)
            );
        }
        if ($onlyWithVisibleSettings) {
            $modules = array_filter(
                $modules,
                fn (string $module) => !$this->getModuleResolver($module)->areSettingsHidden($module)
            );
        }
        return array_values($modules);
    }
    /**
     * @throws ModuleNotExistsException If module does not exist
     */
    public function getModuleResolver(string $module): ModuleResolverInterface
    {
        if (!isset($this->moduleResolversByModule[$module])) {
            throw new ModuleNotExistsException(sprintf(
                \__('Module \'%s\' does not exist', 'graphql-api'),
                $module
            ));
        }
        return $this->moduleResolversByModule[$module];
    }
    public function isModuleEnabled(string $module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);

        // If the state is predefined, then already return it
        $isPredefinedEnabledOrDisabled = $moduleResolver->isPredefinedEnabledOrDisabled($module);
        if ($isPredefinedEnabledOrDisabled !== null) {
            return $isPredefinedEnabledOrDisabled;
        }

        // Check that all requirements are satisfied
        if (!$moduleResolver->areRequirementsSatisfied($module)) {
            return false;
        }

        // Check that all depended-upon modules are enabled
        if (!$this->areDependedModulesEnabled($module)) {
            return false;
        }

        // Check if the value has been saved on the DB
        $moduleID = $moduleResolver->getID($module);
        if ($this->getUserSettingsManager()->hasSetModuleEnabled($moduleID)) {
            return $this->getUserSettingsManager()->isModuleEnabled($moduleID);
        }

        // Get the default value from the resolver
        return $moduleResolver->isEnabledByDefault($module);
    }

    /**
     * Indicate if a module's depended-upon modules are all enabled
     */
    protected function areDependedModulesEnabled(string $module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);
        // Check that all depended-upon modules are enabled
        $dependedModuleLists = $moduleResolver->getDependedModuleLists($module);
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
        foreach ($dependedModuleLists as $dependedModuleList) {
            if (!$dependedModuleList) {
                continue;
            }
            $dependedModuleListEnabled = array_map(
                function (string $dependedModule): bool {
                    // Check if it has the "inverse" token at the beginning,
                    // then it depends on the module being disabled, not enabled
                    if (substr($dependedModule, 0, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY)) === ModuleRegistryTokens::INVERSE_DEPENDENCY) {
                        // The module is everything after the token
                        $dependedModule = substr($dependedModule, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY));
                        return !$this->isModuleEnabled($dependedModule);
                    }
                    return $this->isModuleEnabled($dependedModule);
                },
                $dependedModuleList
            );
            if (!in_array(true, $dependedModuleListEnabled)) {
                return false;
            }
        }
        return true;
    }

    /**
     * If a module does not set a predefined enabled/disabled state,
     * then the user can enable/disable it.
     * If a module was disabled by the user, then the user can enable it.
     * If it is disabled because its requirements are not satisfied,
     * or its dependencies themselves disabled, then it cannot be enabled by the user.
     */
    public function canModuleBeEnabled(string $module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);

        // If the state is predefined, then the user can't set the state
        $isPredefinedEnabledOrDisabled = $moduleResolver->isPredefinedEnabledOrDisabled($module);
        if ($isPredefinedEnabledOrDisabled !== null) {
            return false;
        }

        // Check that all requirements are satisfied
        if (!$moduleResolver->areRequirementsSatisfied($module)) {
            return false;
        }

        // Check that all depended-upon modules are enabled
        if (!$this->areDependedModulesEnabled($module)) {
            return false;
        }

        return true;
    }

    /**
     * Used to indicate that the dependency on the module is on its being disabled, not enabled
     */
    public function getInverseDependency(string $dependedModule): string
    {
        // Check if it already has the "inverse" token at the beginning,
        // then take it back to normal
        if ($this->isInverseDependency($dependedModule)) {
            // The module is everything after the token "!"
            return substr($dependedModule, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY));
        }
        // Add "!" before the module
        return ModuleRegistryTokens::INVERSE_DEPENDENCY . $dependedModule;
    }
    /**
     * Indicate if the dependency is on its being disabled, not enabled
     */
    public function isInverseDependency(string $dependedModule): bool
    {
        return substr($dependedModule, 0, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY)) === ModuleRegistryTokens::INVERSE_DEPENDENCY;
    }
}

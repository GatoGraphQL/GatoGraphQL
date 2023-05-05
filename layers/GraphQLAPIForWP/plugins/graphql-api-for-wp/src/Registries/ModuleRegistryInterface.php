<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Exception\ModuleNotExistsException;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;

interface ModuleRegistryInterface
{
    public function addModuleResolver(ModuleResolverInterface $moduleResolver): void;
    /**
     * @return string[]
     */
    public function getAllModules(
        bool $onlyEnabled = false,
        bool $onlyHasSettings = false,
        bool $onlyVisible = false,
        bool $onlyWithVisibleSettings = false,
        ?string $settingsCategory = null,
    ): array;
    /**
     * @throws ModuleNotExistsException If module does not exist
     */
    public function getModuleResolver(string $module): ModuleResolverInterface;
    public function isModuleEnabled(string $module): bool;
    /**
     * If a module does not set a predefined enabled/disabled state,
     * then the user can enable/disable it.
     * If a module was disabled by the user, then the user can enable it.
     * If it is disabled because its requirements are not satisfied,
     * or its dependencies themselves disabled, then it cannot be enabled by the user.
     */
    public function canModuleBeEnabled(string $module): bool;
    /**
     * Used to indicate that the dependency on the module is on its being disabled, not enabled
     */
    public function getInverseDependency(string $dependedModule): string;
    /**
     * Indicate if the dependency is on its being disabled, not enabled
     */
    public function isInverseDependency(string $dependedModule): bool;
}

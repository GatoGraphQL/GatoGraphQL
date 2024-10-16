<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Exception\ComponentNotExistsException;

interface ModuleManagerInterface
{
    /**
     * Register and initialize a module
     *
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     */
    public function register(string $moduleClass): ModuleInterface;

    /**
     * Register a module under a different class
     *
     * @param class-string<ModuleInterface> $moduleClass
     */
    public function registerAs(ModuleInterface $module, string $moduleClass): void;

    /**
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @throws ComponentNotExistsException If the class of the module does not exist or has not been initialized
     */
    public function getModule(string $moduleClass): ModuleInterface;

    /**
     * Configure modules
     */
    public function configureComponents(): void;

    /**
     * Boot all modules
     */
    public function bootSystem(): void;

    /**
     * Boot all modules
     */
    public function moduleLoaded(): void;

    /**
     * Boot all modules
     */
    public function preBoot(): void;

    /**
     * Boot all modules
     */
    public function boot(): void;

    /**
     * Boot all modules
     */
    public function afterBoot(): void;
}

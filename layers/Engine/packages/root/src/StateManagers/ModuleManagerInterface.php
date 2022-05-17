<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Exception\ComponentNotExistsException;

interface ModuleManagerInterface
{
    /**
     * Register and initialize a module
     */
    public function register(string $moduleClass): ModuleInterface;

    /**
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
    public function boot(): void;

    /**
     * Boot all modules
     */
    public function afterBoot(): void;
}

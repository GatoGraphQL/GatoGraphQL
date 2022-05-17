<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Exception\ComponentNotExistsException;

interface ModuleManagerInterface
{
    /**
     * Register and initialize a component
     */
    public function register(string $moduleClass): ModuleInterface;

    /**
     * @throws ComponentNotExistsException If the class of the component does not exist or has not been initialized
     */
    public function getModule(string $moduleClass): ModuleInterface;

    /**
     * Configure components
     */
    public function configureComponents(): void;

    /**
     * Boot all components
     */
    public function bootSystem(): void;

    /**
     * Boot all components
     */
    public function moduleLoaded(): void;

    /**
     * Boot all components
     */
    public function boot(): void;

    /**
     * Boot all components
     */
    public function afterBoot(): void;
}

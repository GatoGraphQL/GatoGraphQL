<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Component\ComponentInterface;
use PoP\Root\Exception\ComponentNotExistsException;

interface ComponentManagerInterface
{
    /**
     * Register and initialize a component
     */
    public function register(string $componentClass): ComponentInterface;

    /**
     * @throws ComponentNotExistsException If the class of the component does not exist or has not been initialized
     */
    public function getComponent(string $componentClass): ComponentInterface;

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
    public function componentLoaded(): void;

    /**
     * Boot all components
     */
    public function boot(): void;

    /**
     * Boot all components
     */
    public function afterBoot(): void;
}

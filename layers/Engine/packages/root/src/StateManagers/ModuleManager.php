<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Exception\ComponentNotExistsException;

/**
 * Keep a reference to all Components
 */
class ModuleManager implements ModuleManagerInterface
{
    /**
     * The initialized components, stored under their class
     *
     * @var array<string,ModuleInterface>
     */
    protected array $components = [];

    /**
     * Register and initialize a component
     */
    public function register(string $moduleClass): ModuleInterface
    {
        $component = new $moduleClass();
        $this->components[$moduleClass] = $component;
        return $component;
    }

    /**
     * @throws ComponentNotExistsException If the class of the component does not exist or has not been initialized
     */
    public function getModule(string $moduleClass): ModuleInterface
    {
        if (!isset($this->components[$moduleClass])) {
            throw new ComponentNotExistsException(\sprintf(
                'Module of class \'%s\' does not exist, or it has not been added for initialization',
                $moduleClass
            ));
        }
        return $this->components[$moduleClass];
    }

    /**
     * Configure components
     */
    public function configureComponents(): void
    {
        foreach ($this->components as $component) {
            if (!$component->isEnabled()) {
                continue;
            }
            $component->configure();
        }
    }

    /**
     * Boot all components
     */
    public function bootSystem(): void
    {
        foreach ($this->components as $component) {
            if (!$component->isEnabled()) {
                continue;
            }
            $component->bootSystem();
        }
    }

    /**
     * Boot all components
     */
    public function componentLoaded(): void
    {
        foreach ($this->components as $component) {
            if (!$component->isEnabled()) {
                continue;
            }
            $component->componentLoaded();
        }
    }

    /**
     * Boot all components
     */
    public function boot(): void
    {
        foreach ($this->components as $component) {
            if (!$component->isEnabled()) {
                continue;
            }
            $component->boot();
        }
    }

    /**
     * Boot all components
     */
    public function afterBoot(): void
    {
        foreach ($this->components as $component) {
            if (!$component->isEnabled()) {
                continue;
            }
            $component->afterBoot();
        }
    }
}

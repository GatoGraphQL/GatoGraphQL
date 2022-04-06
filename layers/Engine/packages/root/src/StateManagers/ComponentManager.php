<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Component\ComponentInterface;
use PoP\Root\Exception\ComponentNotExistsException;

/**
 * Keep a reference to all Components
 */
class ComponentManager implements ComponentManagerInterface
{
    /**
     * The initialized components, stored under their class
     *
     * @var array<string,ComponentInterface>
     */
    protected array $components = [];

    /**
     * Register and initialize a component
     */
    public function register(string $componentClass): ComponentInterface
    {
        $component = new $componentClass();
        $this->components[$componentClass] = $component;
        return $component;
    }

    /**
     * @throws ComponentNotExistsException If the class of the component does not exist or has not been initialized
     */
    public function getComponent(string $componentClass): ComponentInterface
    {
        if (!isset($this->components[$componentClass])) {
            throw new ComponentNotExistsException(\sprintf(
                'Component of class \'%s\' does not exist, or it has not been added for initialization',
                $componentClass
            ));
        }
        return $this->components[$componentClass];
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

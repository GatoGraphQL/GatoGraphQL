<?php

declare(strict_types=1);

namespace PoP\Root\Managers;

use LogicException;
use PoP\Root\Component\ComponentInterface;

/**
 * Keep a reference to all Components
 */
class ComponentManager
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
     * @throws LogicException If the class of the component does not exist or has not been initialized
     */
    public function getComponent(string $componentClass): ComponentInterface
    {
        if (!isset($this->components[$componentClass])) {
            throw new LogicException(\sprintf(
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
            $component->configure();
        }
    }

    /**
     * Boot all components
     */
    public function bootSystem(): void
    {
        foreach ($this->components as $component) {
            $component->bootSystem();
        }
    }

    /**
     * Boot all components
     */
    public function beforeBoot(): void
    {
        foreach ($this->components as $component) {
            $component->beforeBoot();
        }
    }

    /**
     * Boot all components
     */
    public function boot(): void
    {
        foreach ($this->components as $component) {
            $component->boot();
        }
    }

    /**
     * Boot all components
     */
    public function afterBoot(): void
    {
        foreach ($this->components as $component) {
            $component->afterBoot();
        }
    }

    /**
     * Have the components initialize their state on a global, shared way
     */
    public function initializeAppState(): void
    {
        foreach ($this->components as $component) {
            $component->initializeAppState();
        }
    }
}

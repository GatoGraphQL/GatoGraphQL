<?php

declare(strict_types=1);

namespace PoP\Root\Managers;

use LogicException;
use PoP\BasicService\Component\ComponentConfigurationInterface;
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
    protected static array $components = [];

    /**
     * The initialized component configurations, stored under the component's class
     *
     * @var array<string,ComponentConfigurationInterface>
     */
    protected static array $componentConfigurations = [];

    /**
     * This functions is to be called by PHPUnit,
     * to reset the state in between tests.
     *
     * Reset the initialized components.
     */
    public static function reset(): void
    {
        self::$components = [];
        self::$componentConfigurations = [];
    }

    /**
     * Register and initialize a component
     */
    public static function register(string $componentClass): ComponentInterface
    {
        $component = new $componentClass();
        self::$components[$componentClass] = $component;
        return $component;
    }

    /**
     * @throws LogicException If the class of the component does not exist or has not been initialized
     */
    public static function getComponent(string $componentClass): ComponentInterface
    {
        return self::$components[$componentClass] ?? throw new LogicException(\sprintf(
            'Component of class \'%s\' does not exist, or it has not been added for initialization',
            $componentClass
        ));
    }

    /**
     * @throws LogicException If the class of the component does not exist or has not been initialized
     */
    public static function getComponentConfiguration(string $componentClass): ?ComponentConfigurationInterface
    {
        if (!array_key_exists($componentClass, self::$componentConfigurations)) {
            $component = self::getComponent($componentClass);
            $componentConfigurationClass = $component->getComponentConfigurationClass();
            self::$componentConfigurations[$componentClass] = $componentConfigurationClass !== null ? new $componentConfigurationClass() : null;
        }
        return self::$componentConfigurations[$componentClass];
    }

    /**
     * Boot all components
     */
    public static function bootSystem(): void
    {
        foreach (self::$components as $component) {
            $component->bootSystem();
        }
    }

    /**
     * Boot all components
     */
    public static function beforeBoot(): void
    {
        foreach (self::$components as $component) {
            $component->beforeBoot();
        }
    }

    /**
     * Boot all components
     */
    public static function boot(): void
    {
        foreach (self::$components as $component) {
            $component->boot();
        }
    }

    /**
     * Boot all components
     */
    public static function afterBoot(): void
    {
        foreach (self::$components as $component) {
            $component->afterBoot();
        }
    }
}

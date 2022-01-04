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
    protected static array $components = [];

    /**
     * This functions is to be called by PHPUnit,
     * to reset the state in between tests.
     *
     * Reset the initialized components.
     */
    public static function reset(): void
    {
        self::$components = [];
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
        if (!isset(self::$components[$componentClass])) {
            throw new LogicException(\sprintf(
                'Component of class \'%s\' does not exist, or it has not been added for initialization',
                $componentClass
            ));
        }
        return self::$components[$componentClass];
    }

    /**
     * Configure components
     */
    public static function configureComponents(): void
    {
        foreach (self::$components as $component) {
            $component->configure();
        }
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

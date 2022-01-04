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
     * @var string[]
     */
    private static array $componentClasses = [];

    /**
     * The initialized components, stored under their class
     *
     * @var array<string,ComponentInterface>
     */
    protected static array $components = [];

    /**
     * @return string[]
     */
    public static function getComponentClasses(): array
    {
        return self::$componentClasses;
    }

    /**
     * Register and initialize a component
     */
    public static function register(string $componentClass): ComponentInterface
    {
        $component = new $componentClass();
        self::$components[$componentClass] = $component;
        self::$componentClasses[] = $componentClass;
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
     * Boot all components
     */
    public static function bootSystem(): void
    {
        foreach (self::$componentClasses as $componentClass) {
            $componentClass::bootSystem();
        }
    }

    /**
     * Boot all components
     */
    public static function beforeBoot(): void
    {
        foreach (self::$componentClasses as $componentClass) {
            $componentClass::beforeBoot();
        }
    }

    /**
     * Boot all components
     */
    public static function boot(): void
    {
        foreach (self::$componentClasses as $componentClass) {
            $componentClass::boot();
        }
    }

    /**
     * Boot all components
     */
    public static function afterBoot(): void
    {
        foreach (self::$componentClasses as $componentClass) {
            $componentClass::afterBoot();
        }
    }
}

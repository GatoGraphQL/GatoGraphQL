<?php

declare(strict_types=1);

namespace PoP\Root\Managers;

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
     * @return string[]
     */
    public static function getComponentClasses(): array
    {
        return self::$componentClasses;
    }

    /**
     * Register a component
     */
    public static function register(string $componentClass): void
    {
        self::$componentClasses[] = $componentClass;
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

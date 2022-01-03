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
    private static array $components = [];
    
    /**
     * Reset the state. Called during PHPUnit testing.
     */
    public static function reset(): void
    {
        static::$components = [];
    }

    /**
     * @return string[]
     */
    public static function getComponentClasses(): array
    {
        return self::$components;
    }

    /**
     * Register a component
     */
    public static function register(string $component): void
    {
        self::$components[] = $component;
    }

    /**
     * Boot all components
     */
    public static function bootSystem(): void
    {
        foreach (self::$components as $component) {
            $component::bootSystem();
        }
    }

    /**
     * Boot all components
     */
    public static function beforeBoot(): void
    {
        foreach (self::$components as $component) {
            $component::beforeBoot();
        }
    }

    /**
     * Boot all components
     */
    public static function boot(): void
    {
        foreach (self::$components as $component) {
            $component::boot();
        }
    }

    /**
     * Boot all components
     */
    public static function afterBoot(): void
    {
        foreach (self::$components as $component) {
            $component::afterBoot();
        }
    }
}

<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Managers\ComponentManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Single class hosting all the top-level instances to run the application
 */
class App
{
    protected static AppLoader $appLoader;
    protected static ContainerBuilderFactory $containerBuilderFactory;
    protected static SystemContainerBuilderFactory $systemContainerBuilderFactory;
    protected static ComponentManager $componentManager;
    protected static array $componentClassesToInitialize = [];

    /**
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application
     */
    public static function initialize(): void
    {
        self::$appLoader = static::createAppLoader();
        self::$appLoader->addComponentClassesToInitialize(self::$componentClassesToInitialize);
        self::$containerBuilderFactory = static::createContainerBuilderFactory();
        self::$systemContainerBuilderFactory = static::createSystemContainerBuilderFactory();
        self::$componentManager = static::createComponentManager();
        self::$componentClassesToInitialize = [];
    }

    protected static function createAppLoader(): AppLoader
    {
        return new AppLoader();
    }

    protected static function createContainerBuilderFactory(): ContainerBuilderFactory
    {
        return new ContainerBuilderFactory();
    }

    protected static function createSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return new SystemContainerBuilderFactory();
    }

    protected static function createComponentManager(): ComponentManager
    {
        return new ComponentManager();
    }

    public static function getAppLoader(): AppLoader
    {
        return self::$appLoader;
    }

    public static function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        return self::$containerBuilderFactory;
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return self::$systemContainerBuilderFactory;
    }

    public static function getComponentManager(): ComponentManager
    {
        return self::$componentManager;
    }

    final public static function getContainer(): Container
    {
        return self::getContainerBuilderFactory()->getInstance();
    }

    final public static function getSystemContainer(): Container
    {
        return self::getSystemContainerBuilderFactory()->getInstance();
    }

    /**
     * Store Component classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    public static function stockAndInitializeComponentClasses(
        array $componentClasses
    ): void {
        self::$componentClassesToInitialize = array_merge(
            self::$componentClassesToInitialize,
            $componentClasses
        );
    }
}

<?php

declare(strict_types=1);

namespace PoP\Root;

use LogicException;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Managers\ComponentManager;

/**
 * Single class hosting all the top-level instances to run the application
 */
class App
{
    protected static AppLoader $appLoader;
    protected static ContainerBuilderFactory $containerBuilderFactory;
    protected static SystemContainerBuilderFactory $systemContainerBuilderFactory;
    protected static ComponentManager $componentManager;

    /**
     * This functions is to be called by PHPUnit,
     * to reset the state in between tests.
     *
     * Reset the state of the Application.
     */
    public static function reset(): void
    {
        static::initialize();
    }

    /**
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application
     */
    public static function initialize(): void
    {
        self::$appLoader = static::createAppLoader();
        self::$containerBuilderFactory = static::createContainerBuilderFactory();
        self::$systemContainerBuilderFactory = static::createSystemContainerBuilderFactory();
        self::$componentManager = static::createComponentManager();
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
        if (self::$appLoader === null) {
            self::throwAppNotInitializedException();
        }
        return self::$appLoader;
    }

    /**
     * @throws LogicException
     */
    private static function throwAppNotInitializedException(): void
    {
        throw new LogicException(\sprintf('App has not been initialized'));
    }

    public static function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        if (self::$containerBuilderFactory === null) {
            self::throwAppNotInitializedException();
        }
        return self::$containerBuilderFactory;
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        if (self::$systemContainerBuilderFactory === null) {
            self::throwAppNotInitializedException();
        }
        return self::$systemContainerBuilderFactory;
    }

    public static function getComponentManager(): ComponentManager
    {
        if (self::$componentManager === null) {
            self::throwAppNotInitializedException();
        }
        return self::$componentManager;
    }

}

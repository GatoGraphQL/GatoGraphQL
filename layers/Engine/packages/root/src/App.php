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
    protected static ?AppLoader $appLoader = null;
    protected static ?ContainerBuilderFactory $containerBuilderFactory = null;
    protected static ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null;
    protected static ?ComponentManager $componentManager = null;

    /**
     * This functions is to be called by PHPUnit,
     * to reset the state in between tests.
     *
     * Reset the state of the Application.
     */
    public static function reset(): void
    {
        self::$appLoader = null;
        self::$containerBuilderFactory = null;
        self::$systemContainerBuilderFactory = null;
        self::$componentManager = null;
    }

    public static function getAppLoader(): AppLoader
    {
        return self::$appLoader ?? self::throwAppNotInitializedException();
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
        return self::$containerBuilderFactory ?? self::throwAppNotInitializedException();
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return self::$systemContainerBuilderFactory ?? self::throwAppNotInitializedException();
    }

    public static function getComponentManager(): ComponentManager
    {
        return self::$componentManager ?? self::throwAppNotInitializedException();
    }

}

<?php

declare(strict_types=1);

namespace PoP\Root;

use LogicException;
use PoP\Root\Component\ComponentInterface;
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
     * to initialize the instance to run the application.
     *
     * Either inject the desired instance, or have the Root
     * provide the default one.
     */
    public static function initialize(
        ?AppLoader $appLoader = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ComponentManager $componentManager = null,
    ): void {
        self::$appLoader = $appLoader ?? static::createAppLoader();
        self::$containerBuilderFactory = $containerBuilderFactory ?? static::createContainerBuilderFactory();
        self::$systemContainerBuilderFactory = $systemContainerBuilderFactory ?? static::createSystemContainerBuilderFactory();
        self::$componentManager = $componentManager ?? static::createComponentManager();

        // Inject the Components slated for initialization
        self::$appLoader->addComponentClassesToInitialize(self::$componentClassesToInitialize);
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

    /**
     * Shortcut function.
     */
    final public static function getContainer(): Container
    {
        return self::getContainerBuilderFactory()->getInstance();
    }

    /**
     * Shortcut function.
     */
    final public static function getSystemContainer(): Container
    {
        return self::getSystemContainerBuilderFactory()->getInstance();
    }

    /**
     * Shortcut function.
     *
     * @throws LogicException
     */
    final public static function getComponent(string $componentClass): ComponentInterface
    {
        return self::getComponentManager()->getComponent($componentClass);
    }
}

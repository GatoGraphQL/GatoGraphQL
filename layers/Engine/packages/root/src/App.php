<?php

declare(strict_types=1);

namespace PoP\Root;

use LogicException;
use PoP\Root\Component\ComponentInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Managers\AppStateManager;
use PoP\Root\Managers\ComponentManager;
use PoP\Root\State\MutationResolutionStore;
use Symfony\Component\DependencyInjection\Container;

/**
 * Single class hosting all the top-level instances to run the application
 */
class App implements AppInterface
{
    protected static AppLoader $appLoader;
    protected static ContainerBuilderFactory $containerBuilderFactory;
    protected static SystemContainerBuilderFactory $systemContainerBuilderFactory;
    protected static ComponentManager $componentManager;
    protected static AppStateManager $appStateManager;
    protected static MutationResolutionStore $mutationResolutionStore;
    /** @var string[] */
    protected static array $componentClassesToInitialize = [];
    /**
     * Enable services to inject their own state.
     * Useful for PHPUnit tests.
     *
     * @var array<string,mixed>
     */
    public static array $runtimeServices = [];

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
        ?AppStateManager $appStateManager = null,
        ?MutationResolutionStore $mutationResolutionStore = null,
    ): void {
        self::$appLoader = $appLoader ?? static::createAppLoader();
        self::$containerBuilderFactory = $containerBuilderFactory ?? static::createContainerBuilderFactory();
        self::$systemContainerBuilderFactory = $systemContainerBuilderFactory ?? static::createSystemContainerBuilderFactory();
        self::$componentManager = $componentManager ?? static::createComponentManager();
        self::$appStateManager = $appStateManager ?? static::createAppStateManager();
        self::$mutationResolutionStore = $mutationResolutionStore ?? static::createMutationResolutionStore();

        // Inject the Components slated for initialization
        self::$appLoader->addComponentClassesToInitialize(self::$componentClassesToInitialize);
        self::$componentClassesToInitialize = [];

        // Reset the dynamic services
        self::$runtimeServices = [];
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

    protected static function createAppStateManager(): AppStateManager
    {
        return new AppStateManager();
    }

    protected static function createMutationResolutionStore(): MutationResolutionStore
    {
        return new MutationResolutionStore();
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

    public static function getAppStateManager(): AppStateManager
    {
        return self::$appStateManager;
    }

    public static function getMutationResolutionStore(): MutationResolutionStore
    {
        return self::$mutationResolutionStore;
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

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    final public static function getState(string|array $keyOrPath): mixed
    {
        $appStateManager = self::getAppStateManager();
        if (is_array($keyOrPath)) {
            /** @var string[] */
            $path = $keyOrPath;
            return $appStateManager->getUnder($path);
        }
        /** @var string */
        $key = $keyOrPath;
        return $appStateManager->get($key);
    }

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    final public static function hasState(string|array $keyOrPath): mixed
    {
        $appStateManager = self::getAppStateManager();
        if (is_array($keyOrPath)) {
            /** @var string[] */
            $path = $keyOrPath;
            return $appStateManager->hasUnder($path);
        }
        /** @var string */
        $key = $keyOrPath;
        return $appStateManager->has($key);
    }
}

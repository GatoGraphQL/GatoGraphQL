<?php

declare(strict_types=1);

namespace PoP\Root;

use LogicException;
use PoP\Root\Component\ComponentInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\StateManagers\AppStateManager;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\ComponentManager;
use PoP\Root\StateManagers\ComponentManagerInterface;
use PoP\Root\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\Root\Stores\MutationResolutionStore;
use Symfony\Component\DependencyInjection\Container;

/**
 * Single class hosting all the top-level instances to run the application
 */
class App implements AppInterface
{
    protected static AppLoaderInterface $appLoader;
    protected static HookManagerInterface $hookManager;
    protected static ContainerBuilderFactory $containerBuilderFactory;
    protected static SystemContainerBuilderFactory $systemContainerBuilderFactory;
    protected static ComponentManagerInterface $componentManager;
    protected static AppStateManagerInterface $appStateManager;
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
        ?AppLoaderInterface $appLoader = null,
        ?HookManagerInterface $hookManager = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ComponentManagerInterface $componentManager = null,
        ?AppStateManagerInterface $appStateManager = null,
        ?MutationResolutionStore $mutationResolutionStore = null,
    ): void {
        self::$appLoader = $appLoader ?? static::createAppLoader();
        self::$hookManager = $hookManager ?? static::createHookManager();
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

    protected static function createAppLoader(): AppLoaderInterface
    {
        return new AppLoader();
    }

    protected static function createHookManager(): HookManagerInterface
    {
        return new HookManager();
    }

    protected static function createContainerBuilderFactory(): ContainerBuilderFactory
    {
        return new ContainerBuilderFactory();
    }

    protected static function createSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return new SystemContainerBuilderFactory();
    }

    protected static function createComponentManager(): ComponentManagerInterface
    {
        return new ComponentManager();
    }

    protected static function createAppStateManager(): AppStateManagerInterface
    {
        return new AppStateManager();
    }

    protected static function createMutationResolutionStore(): MutationResolutionStore
    {
        return new MutationResolutionStore();
    }

    public static function getAppLoader(): AppLoaderInterface
    {
        return self::$appLoader;
    }

    public static function getHookManager(): HookManagerInterface
    {
        return self::$hookManager;
    }

    public static function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        return self::$containerBuilderFactory;
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return self::$systemContainerBuilderFactory;
    }

    public static function getComponentManager(): ComponentManagerInterface
    {
        return self::$componentManager;
    }

    public static function getAppStateManager(): AppStateManagerInterface
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

    /**
     * Shortcut function.
     */
    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        self::getHookManager()->addFilter($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     */
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return self::getHookManager()->removeFilter($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     */
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        return self::getHookManager()->applyFilters($tag, $value, ...$args);
    }
    /**
     * Shortcut function.
     */
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        self::getHookManager()->addAction($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     */
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return self::getHookManager()->removeAction($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     */
    public function doAction(string $tag, mixed ...$args): void
    {
        self::getHookManager()->doAction($tag, ...$args);
    }
}

<?php

declare(strict_types=1);

namespace PoP\Root;

use LogicException;
use PoP\Root\Component\ComponentInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Managers\AppStateManagerInterface;
use PoP\Root\Managers\ComponentManagerInterface;
use PoP\Root\Managers\HookManagerInterface;
use PoP\Root\Stores\MutationResolutionStore;
use Symfony\Component\DependencyInjection\Container;

/**
 * Single class hosting all the top-level instances to run the application
 */
interface AppInterface
{
    /**
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application.
     *
     * Either inject the desired instance, or have the Root
     * provide the default one.
     */
    public static function initialize(
        ?AppLoader $appLoader = null,
        ?HookManagerInterface $hookManager = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ComponentManagerInterface $componentManager = null,
        ?AppStateManagerInterface $appStateManager = null,
        ?MutationResolutionStore $mutationResolutionStore = null,
    ): void;

    public static function getAppLoader(): AppLoader;

    public static function getHookManager(): HookManagerInterface;

    public static function getContainerBuilderFactory(): ContainerBuilderFactory;

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory;

    public static function getComponentManager(): ComponentManagerInterface;

    public static function getMutationResolutionStore(): MutationResolutionStore;

    /**
     * Store Component classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    public static function stockAndInitializeComponentClasses(
        array $componentClasses
    ): void;

    /**
     * Shortcut function.
     */
    public static function getContainer(): Container;

    /**
     * Shortcut function.
     */
    public static function getSystemContainer(): Container;

    /**
     * Shortcut function.
     *
     * @throws LogicException
     */
    public static function getComponent(string $componentClass): ComponentInterface;
}

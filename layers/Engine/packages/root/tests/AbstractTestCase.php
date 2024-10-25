<?php

declare(strict_types=1);

namespace PoP\Root;

use PHPUnit\Framework\TestCase;
use PoP\Root\Container\ContainerCacheConfiguration;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;
use PoP\Root\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractTestCase extends TestCase
{
    protected static ContainerInterface $container;

    public static function setUpBeforeClass(): void
    {
        static::initializeApp(false, null, null, true);
    }

    final protected static function initializeApp(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
        bool $isDev = false
    ): void {
        App::setAppThread(static::getAppThread());
        App::initialize(
            static::getAppLoader(),
            static::getHookManager(),
        );
        $appLoader = App::getAppLoader();
        $appLoader->addModuleClassesToInitialize(static::getModuleClassesToInitialize());
        $appLoader->initializeModules($isDev);
        $containerCacheConfiguration = new ContainerCacheConfiguration(
            'PHPUnitTest',
            $cacheContainerConfiguration,
            $containerNamespace,
            $containerDirectory
        );
        $appLoader->setContainerCacheConfiguration($containerCacheConfiguration);
        $appLoader->bootSystem();

        /**
         * Only after initializing the System Container,
         * we can obtain the configuration (which may depend on hooks).
         */
        $appLoader->addModuleClassConfiguration(
            static::getModuleClassConfiguration()
        );

        $appLoader->bootApplication();

        // By now, we already have the container
        self::$container = App::getContainer();

        // Allow to modify the $_GET when testing
        static::beforeBootApplicationModules();

        // Finish the initialization
        $appLoader->bootApplicationModules();
    }

    /**
     * Allow to modify the $_GET when testing.
     */
    protected static function beforeBootApplicationModules(): void
    {
        // Do nothing
    }

    protected static function getAppThread(): AppThreadInterface
    {
        return new AppThread();
    }

    protected static function getAppLoader(): AppLoaderInterface
    {
        return new AppLoader();
    }

    protected static function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getModuleClassesToInitialize(): array
    {
        return [
            static::getModuleClass(),
        ];
    }

    /**
     * Add configuration for the Module classes
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected static function getModuleClassConfiguration(): array
    {
        return [];
    }

    /**
     * Package's Module class, of type ModuleInterface.
     * By standard, it is "NamespaceOwner\Project\Module::class"
     *
     * @return string
     * @phpstan-return class-string<ModuleInterface>
     */
    protected static function getModuleClass(): string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        /** @var class-string<ModuleInterface> */
        return $classNamespace . '\\Module';
    }

    protected function getService(string $service): mixed
    {
        return self::$container->get($service);
    }
}

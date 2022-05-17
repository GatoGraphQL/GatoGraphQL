<?php

declare(strict_types=1);

namespace PoP\Root;

use PHPUnit\Framework\TestCase;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractTestCase extends TestCase
{
    protected static ?ContainerInterface $container = null;

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
        App::initialize(
            static::getAppLoader(),
            static::getHookManager(),
        );
        App::getAppLoader()->addModuleClassesToInitialize(static::getModuleClassesToInitialize());
        App::getAppLoader()->initializeModules($isDev);
        App::getAppLoader()->bootSystem($cacheContainerConfiguration, $containerNamespace, $containerDirectory);

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        App::getAppLoader()->addModuleClassConfiguration(
            static::getModuleClassConfiguration()
        );

        App::getAppLoader()->bootApplication($cacheContainerConfiguration, $containerNamespace, $containerDirectory);

        // By now, we already have the container
        self::$container = App::getContainer();

        // Allow to modify the $_GET when testing
        static::beforeBootApplicationModules();

        // Finish the initialization
        App::getAppLoader()->bootApplicationModules();
    }

    /**
     * Allow to modify the $_GET when testing.
     */
    protected static function beforeBootApplicationModules(): void
    {
        // Do nothing
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
     * @return string[]
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
     * @return array<string, mixed> [key]: Module class, [value]: Configuration
     */
    protected static function getModuleClassConfiguration(): array
    {
        return [];
    }

    /**
     * Package's Module class, of type ModuleInterface.
     * By standard, it is "NamespaceOwner\Project\Module::class"
     */
    protected static function getModuleClass(): string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        return $classNamespace . '\\Module';
    }

    public static function tearDownAfterClass(): void
    {
        self::$container = null;
    }

    protected function getService(string $service): mixed
    {
        return self::$container->get($service);
    }
}

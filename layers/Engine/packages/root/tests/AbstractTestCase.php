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

    private static function initializeApp(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
        bool $isDev = false
    ): void {
        App::initialize(
            static::getAppLoader(),
            static::getHookManager(),
        );
        App::getAppLoader()->addComponentClassesToInitialize(static::getComponentClassesToInitialize());
        App::getAppLoader()->initializeComponents($isDev);
        App::getAppLoader()->bootSystem($cacheContainerConfiguration, $containerNamespace, $containerDirectory);

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        App::getAppLoader()->addComponentClassConfiguration(
            static::getComponentClassConfiguration()
        );

        App::getAppLoader()->bootApplication($cacheContainerConfiguration, $containerNamespace, $containerDirectory);

        // By now, we already have the container
        self::$container = App::getContainer();

        // Allow to modify the $_GET when testing
        static::beforeBootApplicationComponents();

        // Finish the initialization
        App::getAppLoader()->bootApplicationComponents();
    }

    /**
     * Allow to modify the $_GET when testing.
     */
    protected static function beforeBootApplicationComponents(): void
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
    protected static function getComponentClassesToInitialize(): array
    {
        return [
            static::getComponentClass(),
        ];
    }

    /**
     * Add configuration for the Component classes
     *
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    protected static function getComponentClassConfiguration(): array
    {
        return [];
    }

    /**
     * Package's Component class, of type ComponentInterface.
     * By standard, it is "NamespaceOwner\Project\Component::class"
     */
    protected static function getComponentClass(): string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        return $classNamespace . '\\Component';
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

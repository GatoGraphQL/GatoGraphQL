<?php

declare(strict_types=1);

namespace PoP\Root;

use PHPUnit\Framework\TestCase;
use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractTestCase extends TestCase
{
    private static ?ContainerInterface $container = null;

    public static function setUpBeforeClass(): void
    {
        if (self::$container === null) {
            static::initializeApp(false, null, null, true);
            self::$container = App::getContainerBuilderFactory()->getInstance();
        }
    }

    protected static function initializeApp(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
        bool $isDev = false
    ): void {
        $app = static::getAppClass();
        $app::initialize();
        $app::getAppLoader()->addComponentClassesToInitialize(static::getComponentClassesToInitialize());
        $app::getAppLoader()->initializeComponents($isDev);
        $app::getAppLoader()->bootSystem($cacheContainerConfiguration, $containerNamespace, $containerDirectory);

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        $app::getAppLoader()->addComponentClassConfiguration(
            static::getComponentClassConfiguration()
        );

        $app::getAppLoader()->bootApplication($cacheContainerConfiguration, $containerNamespace, $containerDirectory);
    }

    protected static function getAppClass(): string
    {
        return App::class;
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

<?php

declare(strict_types=1);

namespace PoP\Root\Testing\PHPUnit;

use LogicException;
use PHPUnit\Framework\TestCase;
use PoP\Root\AppLoader;
use PoP\Root\Container\ContainerBuilderFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractTestCase extends TestCase
{
    private static ?ContainerInterface $container = null;

    protected static final function initializeContainer(): void
    {
        $componentClass = static::getComponentClass();
        static::initializeAppLoader($componentClass, false, null, null, true);
        static::$container = ContainerBuilderFactory::getInstance();
    }

    protected static function getAppLoaderClass(): string
    {
        return AppLoader::class;
    }

    protected static function initializeAppLoader(
        string $componentClass,
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
        bool $isDev = false
    ): void {
        $appLoader = static::getAppLoaderClass();
        $appLoader::addComponentClassesToInitialize([$componentClass]);
        $appLoader::bootSystem($cacheContainerConfiguration, $containerNamespace, $containerDirectory, $isDev);

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        $appLoader::addComponentClassConfiguration(
            static::getComponentClassConfiguration()
        );
        
        $appLoader::bootApplication($cacheContainerConfiguration, $containerNamespace, $containerDirectory, $isDev);
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
        $class = \get_called_class();
        $parts = \explode('\\', $class);
        if (\count($parts) < 3) {
            throw new LogicException(
                sprintf(
                    'Could not deduce the package Component class from "%s". Must override function "%s"?',
                    $class,
                    __FUNCTION__
                )
            );
        }
        return $parts[0] . '\\' . $parts[1] . '\\Component';
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (static::$container === null) {
            static::initializeContainer();
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (!$this->keepContainerAcrossTests()) {
            static::$container = null;
            $appLoader = static::getAppLoaderClass();
            $appLoader::reset();
        }
    }

    protected function keepContainerAcrossTests(): bool
    {
        return false;
    }

    protected static function getService(string $service): mixed
    {
        return static::$container->get($service);
    }
}

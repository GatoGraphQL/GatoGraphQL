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
        $appLoaderClass = static::getAppLoaderClass();
        $appLoaderClass::addComponentClassesToInitialize([$componentClass]);
        $appLoaderClass::bootSystem($cacheContainerConfiguration, $containerNamespace, $containerDirectory, $isDev);

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        $appLoaderClass::addComponentClassConfiguration(
            static::getComponentClassConfiguration()
        );
        
        $appLoaderClass::bootApplication($cacheContainerConfiguration, $containerNamespace, $containerDirectory, $isDev);
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
        static::$container = null;
    }

    protected static function getService(string $service): mixed
    {
        return static::$container->get($service);
    }

    // /**
    //  * Provides a dedicated test container with access to both public and private
    //  * services. The container will not include private services that have been
    //  * inlined or removed. Private services will be removed when they are not
    //  * used by other services.
    //  *
    //  * Using this method is the best way to get a container from your test code.
    //  */
    // protected static function getContainer(): ContainerInterface
    // {
    //     if (!static::$booted) {
    //         static::initializeContainer();
    //     }

    //     try {
    //         return static::$container->get('test.service_container');
    //     } catch (ServiceNotFoundException $e) {
    //         throw new \LogicException('Could not find service "test.service_container". Try updating the "framework.test" config to "true".', 0, $e);
    //     }
    // }
}

<?php

/*
 * Copied from the Symfony package.
 *
 * @see https://raw.githubusercontent.com/symfony/symfony/6.0/src/Symfony/Bundle/FrameworkBundle/Test/KernelTestCase.php
 */

namespace PoP\Root\Testing\PHPUnit;

use PHPUnit\Framework\TestCase;
use PoP\Engine\AppLoader;
use PoP\Root\Container\ContainerBuilderFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractIntegrationTestCase extends TestCase
{
    private static ?ContainerInterface $container = null;

    protected static final function initializeContainer(): void
    {
        $componentClasses = static::getDependedComponentClasses();
        AppLoader::addComponentClassesToInitialize($componentClasses);
        AppLoader::bootSystem(false, null, null, true);
        AppLoader::bootApplication(false, null, null, true);

        static::$container = ContainerBuilderFactory::getInstance();;
    }

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    abstract protected static function getDependedComponentClasses(): array;

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

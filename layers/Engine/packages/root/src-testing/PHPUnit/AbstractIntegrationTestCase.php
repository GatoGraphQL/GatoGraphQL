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
    private static bool $booted = false;
    private static ?ContainerInterface $container = null;

    /**
     * Boots the Kernel for this test.
     */
    private static final function initializeContainer(): void
    {
        $componentClasses = [
            \PoP\GraphQLParser\Component::class,
        ];
        AppLoader::addComponentClassesToInitialize($componentClasses);
        AppLoader::bootSystem(false, null, null, true);
        AppLoader::bootApplication(false, null, null, true);

        self::$container = ContainerBuilderFactory::getInstance();;
        self::$booted = true;
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$booted) {
            self::initializeContainer();
        }
    }

    protected static function getService(string $service): mixed
    {
        return self::$container->get($service);
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
    //         self::initializeContainer();
    //     }

    //     try {
    //         return self::$container->get('test.service_container');
    //     } catch (ServiceNotFoundException $e) {
    //         throw new \LogicException('Could not find service "test.service_container". Try updating the "framework.test" config to "true".', 0, $e);
    //     }
    // }
}

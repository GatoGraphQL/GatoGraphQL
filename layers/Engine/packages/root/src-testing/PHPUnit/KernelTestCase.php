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
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Contracts\Service\ResetInterface;

/**
 * KernelTestCase is the base class for tests needing a Kernel.
 */
abstract class KernelTestCase extends TestCase
{
    protected static $booted = false;

    private static ?ContainerInterface $kernelContainer = null;

    protected function tearDown(): void
    {
        static::ensureKernelShutdown();
        static::$booted = false;
    }

    /**
     * Boots the Kernel for this test.
     */
    protected static function bootKernel(array $options = []): void
    {
        static::ensureKernelShutdown();

        $componentClasses = [
            \PoP\GraphQLParser\Component::class,
        ];
        AppLoader::addComponentClassesToInitialize($componentClasses);
        AppLoader::bootSystem(false, null, null, true);
        AppLoader::bootApplication(false, null, null, true);

        static::$booted = true;

        self::$kernelContainer = ContainerBuilderFactory::getInstance();
    }

    /**
     * Provides a dedicated test container with access to both public and private
     * services. The container will not include private services that have been
     * inlined or removed. Private services will be removed when they are not
     * used by other services.
     *
     * Using this method is the best way to get a container from your test code.
     */
    protected static function getContainer(): ContainerInterface
    {
        if (!static::$booted) {
            static::bootKernel();
        }

        try {
            return self::$kernelContainer->get('test.service_container');
        } catch (ServiceNotFoundException $e) {
            throw new \LogicException('Could not find service "test.service_container". Try updating the "framework.test" config to "true".', 0, $e);
        }
    }

    /**
     * Shuts the kernel down if it was used in the test - called by the tearDown method by default.
     */
    protected static function ensureKernelShutdown()
    {
        static::$booted = false;

        if (self::$kernelContainer instanceof ResetInterface) {
            self::$kernelContainer->reset();
        }

        self::$kernelContainer = null;
    }
}

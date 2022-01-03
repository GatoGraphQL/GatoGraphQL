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
    protected static $class;

    // /**
    //  * @var KernelInterface
    //  */
    // protected static $kernel;

    protected static $booted = false;

    private static ?ContainerInterface $kernelContainer = null;

    protected function tearDown(): void
    {
        static::ensureKernelShutdown();
        // static::$kernel = null;
        static::$booted = false;
    }

    // /**
    //  * @throws \RuntimeException
    //  * @throws \LogicException
    //  */
    // protected static function getKernelClass(): string
    // {
    //     if (!isset($_SERVER['KERNEL_CLASS']) && !isset($_ENV['KERNEL_CLASS'])) {
    //         throw new \LogicException(sprintf('You must set the KERNEL_CLASS environment variable to the fully-qualified class name of your Kernel in phpunit.xml / phpunit.xml.dist or override the "%1$s::createKernel()" or "%1$s::getKernelClass()" method.', static::class));
    //     }

    //     if (!class_exists($class = $_ENV['KERNEL_CLASS'] ?? $_SERVER['KERNEL_CLASS'])) {
    //         throw new \RuntimeException(sprintf('Class "%s" doesn\'t exist or cannot be autoloaded. Check that the KERNEL_CLASS value in phpunit.xml matches the fully-qualified class name of your Kernel or override the "%s::createKernel()" method.', $class, static::class));
    //     }

    //     return $class;
    // }

    // /**
    //  * Boots the Kernel for this test.
    //  */
    // protected static function bootKernel(array $options = []): KernelInterface
    // {
    //     static::ensureKernelShutdown();

    //     static::$kernel = static::createKernel($options);
    //     static::$kernel->boot();
    //     static::$booted = true;

    //     self::$kernelContainer = static::$kernel->getContainer();

    //     return static::$kernel;
    // }
    /**
     * Boots the Kernel for this test.
     */
    protected static function bootKernel(array $options = []): void
    {
        static::ensureKernelShutdown();

        // static::$kernel = static::createKernel($options);
        // static::$kernel->boot();
        // ContainerBuilderFactory::init();
        $componentClasses = [
            \PoP\GraphQLParser\Component::class,
        ];
        AppLoader::addComponentClassesToInitialize($componentClasses);
        AppLoader::bootSystem();
        AppLoader::bootApplication();
        static::$booted = true;

        self::$kernelContainer = ContainerBuilderFactory::getInstance();

        // return static::$kernel;
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

    // /**
    //  * Creates a Kernel.
    //  *
    //  * Available options:
    //  *
    //  *  * environment
    //  *  * debug
    //  */
    // protected static function createKernel(array $options = []): KernelInterface
    // {
    //     if (null === static::$class) {
    //         static::$class = static::getKernelClass();
    //     }

    //     if (isset($options['environment'])) {
    //         $env = $options['environment'];
    //     } elseif (isset($_ENV['APP_ENV'])) {
    //         $env = $_ENV['APP_ENV'];
    //     } elseif (isset($_SERVER['APP_ENV'])) {
    //         $env = $_SERVER['APP_ENV'];
    //     } else {
    //         $env = 'test';
    //     }

    //     if (isset($options['debug'])) {
    //         $debug = $options['debug'];
    //     } elseif (isset($_ENV['APP_DEBUG'])) {
    //         $debug = $_ENV['APP_DEBUG'];
    //     } elseif (isset($_SERVER['APP_DEBUG'])) {
    //         $debug = $_SERVER['APP_DEBUG'];
    //     } else {
    //         $debug = true;
    //     }

    //     return new static::$class($env, $debug);
    // }

    /**
     * Shuts the kernel down if it was used in the test - called by the tearDown method by default.
     */
    protected static function ensureKernelShutdown()
    {
        // if (null !== static::$kernel) {
            // static::$kernel->shutdown();
            static::$booted = false;
        // }

        if (self::$kernelContainer instanceof ResetInterface) {
            self::$kernelContainer->reset();
        }

        self::$kernelContainer = null;
    }
}

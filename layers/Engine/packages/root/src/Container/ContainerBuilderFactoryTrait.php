<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use InvalidArgumentException;
use LogicException;
use PoP\Root\Environment;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

trait ContainerBuilderFactoryTrait
{
    protected static ?Container $instance = null;
    protected static ?bool $cacheContainerConfiguration = null;
    protected static ?bool $cached = null;
    protected static ?string $cacheFile = null;

    /**
     * This functions is to be called by PHPUnit,
     * to reset the state in between tests.
     *
     * Reset the container.
     */
    public static function reset(): void
    {
        static::$instance = null;
        static::$cacheContainerConfiguration = null;
        static::$cached = null;
        static::$cacheFile = null;
    }

    /**
     * Initialize the Container Builder.
     * If the directory is not provided, store the cache in a system temp dir
     *
     * @param bool|null $cacheContainerConfiguration Indicate if to cache the container configuration. If null, the default value is used
     * @param string|null $namespace subdirectory under which to store the cache. If null, it will use a system temp folder
     * @param string|null $directory directory where to store the cache. If null, the default value is used
     */
    public static function init(
        ?bool $cacheContainerConfiguration = null,
        ?string $namespace = null,
        ?string $directory = null
    ): void {
        static::$cacheContainerConfiguration = $cacheContainerConfiguration ?? Environment::cacheContainerConfiguration();
        $namespace ??= Environment::getCacheContainerConfigurationNamespace();
        $directory ??= Environment::getCacheContainerConfigurationDirectory();
        $throwExceptionIfCacheSetupError = Environment::throwExceptionIfCacheSetupError();
        $cacheSetupSuccess = true;
        $containerClass = $containerNamespace = null;

        if (static::$cacheContainerConfiguration) {
            /**
             * Code copied from Symfony FilesystemAdapter
             * @see https://github.com/symfony/cache/blob/master/Traits/FilesystemCommonTrait.php
             */
            if (!$directory) {
                $directory = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'pop' . \DIRECTORY_SEPARATOR . 'container-cache';
            }
            if ($namespace) {
                if (preg_match('#[^-+_.A-Za-z0-9]#', $namespace, $match)) {
                    if ($throwExceptionIfCacheSetupError) {
                        throw new InvalidArgumentException(
                            sprintf(
                                'Namespace contains "%s" but only characters in [-+_.A-Za-z0-9] are allowed.',
                                $match[0]
                            )
                        );
                    }
                    $cacheSetupSuccess = false;
                }
                $directory .= \DIRECTORY_SEPARATOR . $namespace;
            }
            if ($cacheSetupSuccess && !is_dir($directory)) {
                if (@mkdir($directory, 0777, true) === false) {
                    if ($throwExceptionIfCacheSetupError) {
                        throw new \RuntimeException(sprintf(
                            'The directory %s could not be created.',
                            $directory
                        ));
                    }
                    $cacheSetupSuccess = false;
                }
            }
            $directory .= \DIRECTORY_SEPARATOR;
            // Since we have 2 containers, store each under its namespace and classname
            $containerNamespace = static::getContainerNamespace();
            $containerClass = static::getContainerClass();
            $directory .= $containerNamespace . \DIRECTORY_SEPARATOR;
            $directory .= $containerClass . \DIRECTORY_SEPARATOR;
            // On Windows the whole path is limited to 258 chars
            if ($cacheSetupSuccess && '\\' === \DIRECTORY_SEPARATOR && \strlen($directory) > 234) {
                if ($throwExceptionIfCacheSetupError) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'Cache directory too long (%s).',
                            $directory
                        )
                    );
                }
                $cacheSetupSuccess = false;
            }
        }

        if (static::$cacheContainerConfiguration && $cacheSetupSuccess) {
            // Store the cache under this file
            static::$cacheFile = $directory . 'container.php';

            // If not caching the container, then it's for development
            $isDebug = !static::$cacheContainerConfiguration;
            $containerConfigCache = new ConfigCache(static::$cacheFile, $isDebug);
            static::$cached = $containerConfigCache->isFresh();
        } else {
            static::$cached = false;
            static::$cacheContainerConfiguration = false;
        }

        // If not cached, then create the new instance
        if (!static::$cached) {
            static::$instance = new ContainerBuilder();
        } else {
            require_once static::$cacheFile;
            $containerFullyQuantifiedClass = "\\{$containerNamespace}\\{$containerClass}";
            static::$instance = new $containerFullyQuantifiedClass();
        }
    }
    public static function getInstance(): Container
    {
        if (static::$instance === null) {
            static::throwContainerNotInitializedException();
        }
        return static::$instance;
    }
    /**
     * @throws LogicException
     */
    protected static function throwContainerNotInitializedException(): void
    {
        throw new LogicException('Container has not been initialized');
    }
    public static function isCached(): bool
    {
        if (static::$cached === null) {
            static::throwContainerNotInitializedException();
        }
        return static::$cached;
    }

    public static function getContainerNamespace(): string
    {
        return 'PoPContainer';
    }

    abstract public static function getContainerClass(): string;

    /**
     * If the container is not cached, then compile it and cache it
     *
     * @param CompilerPassInterface[] $compilerPasses Compiler Pass objects to register on the container
     */
    public static function maybeCompileAndCacheContainer(
        array $compilerPasses = []
    ): void {
        // Compile Symfony's DependencyInjection Container Builder
        // After compiling, cache it in disk for performance.
        // This happens only the first time the site is accessed on the current server
        if (!static::$cached) {
            /** @var ContainerBuilder */
            $containerBuilder = static::getInstance();
            // Inject all the compiler passes
            foreach ($compilerPasses as $compilerPass) {
                $containerBuilder->addCompilerPass($compilerPass);
            }
            // Compile the container
            $containerBuilder->compile();

            // Cache the container
            if (static::$cacheContainerConfiguration) {
                // Create the folder if it doesn't exist, and check it was successful
                $dir = dirname(static::$cacheFile);
                $folderExists = file_exists($dir);
                if (!$folderExists) {
                    $folderExists = @mkdir($dir, 0777, true);
                }
                if ($folderExists) {
                    // Save the container to disk
                    $dumper = new PhpDumper($containerBuilder);
                    file_put_contents(
                        static::$cacheFile,
                        $dumper->dump(
                            // Save under own namespace to avoid conflicts
                            [
                                'namespace' => static::getContainerNamespace(),
                                'class' => static::getContainerClass(),
                            ]
                        )
                    );

                    // Change the permissions so it can be modified by external processes (eg: deployment)
                    chmod(static::$cacheFile, 0777);
                }
            }
        }
    }
}

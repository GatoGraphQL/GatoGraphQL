<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use InvalidArgumentException;
use PoP\Root\Environment;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerBuilderFactory
{
    private static Container $instance;
    private static bool $cacheContainerConfiguration;
    private static bool $cached;
    private static string $cacheFile;

    /**
     * Initialize the Container Builder.
     * If the directory is not provided, store the cache in a system temp dir
     *
     * @param bool $cacheContainerConfiguration Indicate if to cache the container configuration
     * @param string|null $directory directory where to store the cache
     * @param string|null $namespace subdirectory under which to store the cache
     * @return void
     */
    public static function init(
        bool $cacheContainerConfiguration = false,
        ?string $namespace = null,
        ?string $directory = null
    ): void {
        self::$cacheContainerConfiguration = $cacheContainerConfiguration;
        $throwExceptionIfCacheSetupError = Environment::throwExceptionIfCacheSetupError();
        $cacheSetupSuccess = true;
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

        if ($cacheSetupSuccess) {
            // Store the cache under this file
            self::$cacheFile = $directory . 'container.php';

            // If not caching the container, then it's for development
            $isDebug = !self::$cacheContainerConfiguration;
            $containerConfigCache = new ConfigCache(self::$cacheFile, $isDebug);
            self::$cached = $containerConfigCache->isFresh();
        } else {
            self::$cached = false;
            self::$cacheContainerConfiguration = false;
        }

        // If not cached, then create the new instance
        if (!self::$cached) {
            self::$instance = new ContainerBuilder();
        } else {
            require_once self::$cacheFile;
            self::$instance = new \ProjectServiceContainer();
        }
    }
    public static function getInstance(): Container
    {
        return self::$instance;
    }
    public static function isCached(): bool
    {
        return self::$cached;
    }

    public static function maybeCompileAndCacheContainer(): void
    {
        // Compile Symfony's DependencyInjection Container Builder
        // After compiling, cache it in disk for performance.
        // This happens only the first time the site is accessed on the current server
        if (!self::$cached) {
            // Compile the container
            /**
             * @var ContainerBuilder
             */
            $containerBuilder = self::getInstance();
            $containerBuilder->compile();

            // Cache the container
            if (self::$cacheContainerConfiguration) {
                // Create the folder if it doesn't exist, and check it was successful
                $dir = dirname(self::$cacheFile);
                $folderExists = file_exists($dir);
                if (!$folderExists) {
                    $folderExists = @mkdir($dir, 0777, true);
                }
                if ($folderExists) {
                    // Save the container to disk
                    $dumper = new PhpDumper($containerBuilder);
                    file_put_contents(self::$cacheFile, $dumper->dump());

                    // Change the permissions so it can be modified by external processes (eg: deployment)
                    chmod(self::$cacheFile, 0777);
                }
            }
        }
    }
}

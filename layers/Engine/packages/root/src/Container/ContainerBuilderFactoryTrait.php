<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use Exception;
use InvalidArgumentException;
use PoP\Root\AppArchitecture;
use PoP\Root\Container\Dumper\DowngradingPhpDumper;
use PoP\Root\Environment;
use RuntimeException;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

trait ContainerBuilderFactoryTrait
{
    protected ContainerInterface $instance;
    protected bool $cacheContainerConfiguration;
    protected bool $cached;
    protected string $cacheFile;

    /**
     * Initialize the Container Builder.
     * If the directory is not provided, store the cache in a system temp dir
     *
     * @param bool|null $cacheContainerConfiguration Indicate if to cache the container configuration. If null, the default value is used
     * @param string|null $namespace subdirectory under which to store the cache. If null, it will use a system temp folder
     * @param string|null $directory directory where to store the cache. If null, the default value is used
     */
    public function init(
        ?bool $cacheContainerConfiguration = null,
        ?string $namespace = null,
        ?string $directory = null
    ): void {
        $this->cacheContainerConfiguration = $cacheContainerConfiguration ?? Environment::cacheContainerConfiguration();
        $namespace ??= Environment::getCacheContainerConfigurationNamespace();
        $directory ??= Environment::getCacheContainerConfigurationDirectory();
        $throwExceptionIfCacheSetupError = Environment::throwExceptionIfCacheSetupError();
        $cacheSetupSuccess = true;
        $containerClassName = $containerNamespace = null;

        if ($this->cacheContainerConfiguration) {
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
                        throw new RuntimeException(sprintf(
                            'The directory %s could not be created.',
                            $directory
                        ));
                    }
                    $cacheSetupSuccess = false;
                }
            }
            $directory .= \DIRECTORY_SEPARATOR;
            // Since we have 2 containers, store each under its namespace and classname
            $containerNamespace = $this->getContainerNamespace();
            $containerClassName = $this->getContainerClassName();
            $directory .= $containerNamespace . \DIRECTORY_SEPARATOR;
            $directory .= $containerClassName . \DIRECTORY_SEPARATOR;
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

        if ($this->cacheContainerConfiguration && $cacheSetupSuccess) {
            // Store the cache under this file
            $this->cacheFile = $directory . 'container.php';

            // If not caching the container, then it's for development
            $isDebug = !$this->cacheContainerConfiguration;
            $containerConfigCache = new ConfigCache($this->cacheFile, $isDebug);
            $this->cached = $containerConfigCache->isFresh();
        } else {
            $this->cached = false;
            $this->cacheContainerConfiguration = false;
        }

        // If not cached, then create the new instance
        if (!$this->cached) {
            $this->instance = new ContainerBuilder();
        } else {
            /**
             * If for some reason the cached file was malformed,
             * and the class cannot be instantiated, this will
             * throw an Exception. Let it explode for DEV, but
             * catch it for PROD and fallback to non-cached method.
             */
            try {
                require_once $this->cacheFile;
                /** @var class-string<ContainerBuilder> */
                $containerFullyQuantifiedClass = "\\{$containerNamespace}\\{$containerClassName}";
                $this->instance = new $containerFullyQuantifiedClass();
            } catch (Exception $e) {
                if (Environment::isApplicationEnvironmentDev()) {
                    throw $e;
                }
                // Delete the cache file, and instantiate container as normal
                \unlink($this->cacheFile);
                $this->instance = new ContainerBuilder();
            }
        }
    }
    public function getInstance(): ContainerInterface
    {
        return $this->instance;
    }

    public function isCached(): bool
    {
        return $this->cached;
    }

    public function getContainerNamespace(): string
    {
        return 'PoPContainer';
    }

    abstract public function getContainerClassName(): string;

    /**
     * If the container is not cached, then compile it and cache it
     *
     * @param CompilerPassInterface[] $compilerPasses Compiler Pass objects to register on the container
     */
    public function maybeCompileAndCacheContainer(
        array $compilerPasses = []
    ): void {
        /**
         * Compile Symfony's DependencyInjection Container Builder.
         *
         * After compiling, cache it in disk for performance.
         *
         * This happens only the first time the site is accessed
         * on the current server.
         */
        if ($this->cached) {
            return;
        }

        /** @var ContainerBuilder */
        $containerBuilder = $this->getInstance();
        // Inject all the compiler passes
        foreach ($compilerPasses as $compilerPass) {
            $containerBuilder->addCompilerPass($compilerPass);
        }

        /**
         * On a (cheap) shared hosting, this script might take too long
         * to execute, reaching the max time limit, which is a fatal error.
         * Avoid it!
         *
         * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2631
         */
        \set_time_limit(0); // 0 = no limits

        // Compile the container.
        $containerBuilder->compile();

        // Cache the container
        if (!$this->cacheContainerConfiguration) {
            return;
        }
        // Create the folder if it doesn't exist, and check it was successful
        $dir = dirname($this->cacheFile);
        $folderExists = file_exists($dir);
        if (!$folderExists) {
            $folderExists = @mkdir($dir, 0777, true);
            if (!$folderExists) {
                // @todo Throw an error!?
                return;
            }
        }

        // Save the container to disk
        $dumper = $this->createPHPDumper($containerBuilder);
        file_put_contents(
            $this->cacheFile,
            $dumper->dump(
                [
                    'class' => $this->getContainerClassName(),
                    // Save under own namespace to avoid conflicts
                    'namespace' => $this->getContainerNamespace(),
                    /**
                     * Extend from own Container since it must implement ContainerInterface.
                     * It must start with "\", or PhpDumper will also prepend "PoPContainer\\"
                     */
                    'base_class' => '\\' . Container::class,
                ]
            )
        );

        // Change the permissions so it can be modified by external processes (eg: deployment)
        chmod($this->cacheFile, 0777);
    }

    /**
     * If downgrading the PHP Code, then use the adapted
     * DowngradingPhpDumper class, as to also downgrade
     * the code generated for the container
     */
    protected function createPHPDumper(ContainerBuilder $containerBuilder): PhpDumper
    {
        return AppArchitecture::isDowngraded()
            ? new DowngradingPhpDumper($containerBuilder)
            : new PhpDumper($containerBuilder);
    }
}

<?php

declare(strict_types=1);

namespace PoP\Root\Module;

use PoP\Root\App;
use PoP\Root\Container\Loader\ForceAutoconfigureYamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

trait InitializeContainerServicesInModuleTrait
{
    /**
     * Initialize the services defined in the YAML configuration file.
     * If not provided, use "services.yaml"
     */
    public function initServices(
        string $moduleDir,
        string $configPath = '',
        string $fileName = 'services.yaml'
    ): void {
        // First check if the container has been cached. If so, do nothing
        if (!App::getContainerBuilderFactory()->isCached()) {
            // Initialize the ContainerBuilder with this module's service implementations
            /** @var ContainerBuilder */
            $containerBuilder = App::getContainer();
            $this->loadServicesFromYAMLConfigIntoContainer($containerBuilder, $moduleDir, $configPath, $fileName);
        }
    }

    /**
     * Initialize the services defined in the YAML configuration file.
     * If not provided, use "services.yaml"
     */
    protected function loadServicesFromYAMLConfigIntoContainer(
        ContainerBuilder $containerBuilder,
        string $moduleDir,
        string $configPath,
        string $fileName
    ): void {
        $modulePath = $this->getComponentPath($moduleDir, $configPath);
        $loader = new YamlFileLoader($containerBuilder, new FileLocator($modulePath));
        $loader->load($fileName);
    }

    /**
     * Initialize the services defined in the YAML configuration file.
     * If not provided, use "services.yaml"
     */
    protected function getComponentPath(
        string $moduleDir,
        string $configPath = ''
    ): string {
        return $moduleDir . '/config' . ($configPath ? '/' . trim($configPath, '/') : '');
    }

    /**
     * If param `$skipSchema` is `true`, define the schema services
     * in the container, but do not initialize them.
     * If file name provided, use "schema-services.yaml"
     */
    public function initSchemaServices(
        string $moduleDir,
        bool $skipSchema,
        string $configPath = '',
        string $fileName = 'schema-services.yaml'
    ): void {
        if (!App::getContainerBuilderFactory()->isCached()) {
            /** @var ContainerBuilder */
            $containerBuilder = App::getContainer();
            $modulePath = $this->getComponentPath($moduleDir, $configPath);
            $autoconfigure = !$skipSchema;
            $loader = new ForceAutoconfigureYamlFileLoader(
                $containerBuilder,
                new FileLocator($modulePath),
                $autoconfigure
            );
            $loader->load($fileName);
        }
    }

    /**
     * Load services into the System Container
     */
    public function initSystemServices(
        string $moduleDir,
        string $configPath = '',
        string $fileName = 'system-services.yaml'
    ): void {
        // First check if the container has been cached. If so, do nothing
        if (!App::getSystemContainerBuilderFactory()->isCached()) {
            // Initialize the ContainerBuilder with this module's service implementations
            /** @var ContainerBuilder */
            $containerBuilder = App::getSystemContainer();
            $this->loadServicesFromYAMLConfigIntoContainer($containerBuilder, $moduleDir, $configPath, $fileName);
        }
    }
}

<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use PoP\Root\Container\ContainerBuilderFactory;

trait YAMLServicesTrait
{
    /**
     * Initialize the services defiend in the YAML configuration file.
     * If not provided, use "services.yaml"
     *
     * @param string $componentDir
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    public static function initYAMLServices(
        string $componentDir,
        string $configPath = '',
        string $fileName = 'services.yaml'
    ): void {
        // First check if the container has been cached. If so, do nothing
        if (!ContainerBuilderFactory::isCached()) {
            // Initialize the ContainerBuilder with this component's service implementations
            $containerBuilder = ContainerBuilderFactory::getInstance();
            $componentPath = $componentDir . '/config' . ($configPath ? '/' . trim($configPath, '/') : '');
            $loader = new YamlFileLoader($containerBuilder, new FileLocator($componentPath));
            $loader->load($fileName);
        }
    }

    /**
     * If param `$skipSchema` is `true`, initialize the schema services defiend in the YAML configuration file.
     * If not provided, use "schema-services.yaml"
     *
     * @param string $componentDir
     * @param boolean $skipSchema
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    public static function maybeInitYAMLSchemaServices(
        string $componentDir,
        bool $skipSchema,
        string $configPath = '',
        string $fileName = 'schema-services.yaml'
    ): void {
        if ($skipSchema) {
            return;
        }
        self::initYAMLServices($componentDir, $configPath, $fileName);
    }
}

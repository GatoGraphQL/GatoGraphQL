<?php

declare(strict_types=1);

namespace PoP\API;

use PoP\API\Configuration\Request;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use CanDisableComponentTrait;

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\Engine\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\AccessControl\Component::class,
            \PoP\CacheControl\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        $packageName = basename(dirname(__DIR__));
        $folder = dirname(__DIR__, 2);
        return [
            $folder . '/migrate-' . $packageName . '/initialize.php',
        ];
    }

    /**
     * Set the default component configuration
     *
     * @param array<string, mixed> $componentClassConfiguration
     */
    public static function customizeComponentClassConfiguration(
        array &$componentClassConfiguration
    ): void {
        // If passing ?use_namespace=1, set it on the configuration
        if (Environment::enableSettingNamespacingByURLParam()) {
            $useNamespacing = Request::namespaceTypesAndInterfaces();
            if ($useNamespacing !== null) {
                $componentClassConfiguration[\PoP\ComponentModel\Component::class][\PoP\ComponentModel\Environment::NAMESPACE_TYPES_AND_INTERFACES] = $useNamespacing;
            }
        }
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if (self::isEnabled()) {
            ComponentConfiguration::setConfiguration($configuration);
            self::initServices(dirname(__DIR__));
            self::initSchemaServices(dirname(__DIR__), $skipSchema);

            // Conditional packages
            if (class_exists('\PoP\AccessControl\Component')) {
                self::initServices(dirname(__DIR__), '/Conditional/AccessControl');
            }
            if (
                class_exists('\PoP\CacheControl\Component')
                && class_exists('\PoP\AccessControl\Component')
                && AccessControlComponentConfiguration::canSchemaBePrivate()
            ) {
                self::initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses) || in_array(\PoP\AccessControl\Component::class, $skipSchemaComponentClasses),
                    '/Conditional/CacheControl/Conditional/AccessControl/ConditionalOnEnvironment/PrivateSchema'
                );
            }
        }
    }

    protected static function resolveEnabled(): bool
    {
        return !Environment::disableAPI();
    }
}

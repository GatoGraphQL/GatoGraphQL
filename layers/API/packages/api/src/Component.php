<?php

declare(strict_types=1);

namespace PoP\API;

use PoP\API\Configuration\Request;
use PoP\API\Config\ServiceConfiguration;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use CanDisableComponentTrait;

    public static $COMPONENT_DIR;
    // const VERSION = '0.1.0';

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
     *
     * @return array
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
            parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
            ComponentConfiguration::setConfiguration($configuration);
            self::$COMPONENT_DIR = dirname(__DIR__);
            self::initYAMLServices(self::$COMPONENT_DIR);
            self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);

            // Conditional packages
            if (class_exists('\PoP\AccessControl\Component')) {
                self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/AccessControl');
            }
            if (
                class_exists('\PoP\CacheControl\Component')
                && !in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses)
                && class_exists('\PoP\AccessControl\Component')
                && !in_array(\PoP\AccessControl\Component::class, $skipSchemaComponentClasses)
                && AccessControlComponentConfiguration::canSchemaBePrivate()
            ) {
                self::maybeInitPHPSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/CacheControl/Conditional/AccessControl/ConditionalOnEnvironment/PrivateSchema');
            }
            ServiceConfiguration::initialize();
        }
    }

    protected static function resolveEnabled()
    {
        return !Environment::disableAPI();
    }
}

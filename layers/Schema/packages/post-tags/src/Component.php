<?php

declare(strict_types=1);

namespace PoPSchema\PostTags;

use PoP\Root\Component\AbstractComponent;
use PoPSchema\PostTags\Config\ServiceConfiguration;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
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
            \PoPSchema\Posts\Component::class,
            \PoPSchema\Tags\Component::class,
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
            \PoP\API\Component::class,
            \PoP\RESTAPI\Component::class,
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
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        ComponentConfiguration::setConfiguration($configuration);
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::initYAMLServices(self::$COMPONENT_DIR);
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);
        ServiceConfiguration::initialize();

        if (class_exists('\PoP\RESTAPI\Component::class') && !in_array(\PoP\RESTAPI\Component::class, $skipSchemaComponentClasses)) {
            self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/RESTAPI');
        }
    }

    /**
     * Define runtime constants
     */
    protected static function defineRuntimeConstants(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if (!defined('POP_POSTTAGS_ROUTE_POSTTAGS')) {
            $definitionManager = DefinitionManagerFacade::getInstance();
            define('POP_POSTTAGS_ROUTE_POSTTAGS', $definitionManager->getUniqueDefinition('post-tags', DefinitionGroups::ROUTES));
        }
    }
}

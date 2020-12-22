<?php

declare(strict_types=1);

namespace PoPSchema\Posts;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoPSchema\Posts\Config\ServiceConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoPSchema\Posts\TypeResolverPickers\Optional\PostCustomPostTypeResolverPicker;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public static $COMPONENT_DIR;

    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\CustomPosts\Component::class,
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
            \PoPSchema\Users\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'pop-schema/migrate-posts',
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

        if (class_exists('\PoPSchema\Users\Component')
            && !in_array(\PoPSchema\Users\Component::class, $skipSchemaComponentClasses)
        ) {
            \PoPSchema\Posts\Conditional\Users\ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }

        // Initialize at the end
        ServiceConfiguration::initialize();
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize classes
        ContainerBuilderUtils::registerTypeResolversFromNamespace(__NAMESPACE__ . '\\TypeResolvers');
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
        self::attachTypeResolverPickers();

        if (class_exists('\PoPSchema\Users\Component')) {
            \PoPSchema\Posts\Conditional\Users\ConditionalComponent::beforeBoot();
        }
    }

    /**
     * If enabled, load the TypeResolverPickers
     *
     * @return void
     */
    protected static function attachTypeResolverPickers()
    {
        if (ComponentConfiguration::addPostTypeToCustomPostUnionTypes()
            // If $skipSchema is `true`, then services are not registered
            && !empty(ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\TypeResolverPickers'))
        ) {
            PostCustomPostTypeResolverPicker::attach(AttachableExtensionGroups::TYPERESOLVERPICKERS);
        }
    }
}

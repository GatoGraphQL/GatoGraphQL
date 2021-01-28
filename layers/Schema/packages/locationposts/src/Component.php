<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts;

use PoPSchema\LocationPosts\Conditional\Tags\ComponentBoot;
use PoPSchema\LocationPosts\Environment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoPSchema\LocationPosts\TypeResolverPickers\Optional\LocationPostCustomPostTypeResolverPicker;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
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
            \PoPSchema\Posts\Component::class,
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
            \PoPSchema\Users\Component::class,
            \PoPSchema\Tags\Component::class,
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
        self::initYAMLServices(dirname(__DIR__));
        self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema);
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

        // Boot conditionals
        if (class_exists('\PoPSchema\Tags\Component')) {
            ComponentBoot::beforeBoot();
        }
        if (class_exists('\PoPSchema\Users\Component')) {
            \PoPSchema\LocationPosts\Conditional\Users\ComponentBoot::beforeBoot();
        }
    }

    /**
     * If enabled, load the TypeResolverPickers
     *
     * @return void
     */
    protected static function attachTypeResolverPickers()
    {
        if (
            Environment::addLocationPostTypeToCustomPostUnionTypes()
            // If $skipSchema is `true`, then services are not registered
            && !empty(ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\TypeResolverPickers'))
        ) {
            LocationPostCustomPostTypeResolverPicker::attach(AttachableExtensionGroups::TYPERESOLVERPICKERS);
        }
    }
}

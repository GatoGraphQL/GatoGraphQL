<?php

declare(strict_types=1);

namespace PoPSchema\Comments;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;

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
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);

        if (class_exists('\PoP\RESTAPI\Component')
            && !in_array(\PoP\RESTAPI\Component::class, $skipSchemaComponentClasses)
        ) {
            \PoPSchema\Comments\Conditional\RESTAPI\ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }

        if (class_exists('\PoPSchema\Users\Component')
            && !in_array(\PoPSchema\Users\Component::class, $skipSchemaComponentClasses)
        ) {
            \PoPSchema\Comments\Conditional\Users\ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize all hooks
        ContainerBuilderUtils::registerTypeResolversFromNamespace(__NAMESPACE__ . '\\TypeResolvers');
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
        ContainerBuilderUtils::registerFieldInterfaceResolversFromNamespace(__NAMESPACE__ . '\\FieldInterfaceResolvers');

        if (class_exists('\PoP\RESTAPI\Component')) {
            \PoPSchema\Comments\Conditional\RESTAPI\ConditionalComponent::beforeBoot();
        }
        if (class_exists('\PoPSchema\Users\Component')) {
            \PoPSchema\Comments\Conditional\Users\ConditionalComponent::beforeBoot();
        }
    }
}

<?php

declare(strict_types=1);

namespace PoP\CacheControl;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\CacheControl\DirectiveResolvers\NestedFieldCacheControlDirectiveResolver;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    use CanDisableComponentTrait;

    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\MandatoryDirectivesByConfiguration\Component::class,
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
        if (self::isEnabled()) {
            parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
            self::initYAMLServices(dirname(__DIR__));
            self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema);
        }
    }

    protected static function resolveEnabled()
    {
        return !Environment::disableCacheControl();
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize directive resolvers, attaching each of them using the right priorities
        // ContainerBuilderUtils::attachAndRegisterDirectiveResolversFromNamespace(__NAMESPACE__.'\\DirectiveResolvers');
        self::setDirectiveResolverPriorities();
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function afterBoot(): void
    {
        parent::afterBoot();

        // Initialize services
        ContainerBuilderUtils::attachTypeResolverDecoratorsFromNamespace(__NAMESPACE__ . '\\TypeResolverDecorators');
    }

    /**
     * Sets the right priority for the directive resolvers
     *
     * @return void
     */
    protected static function setDirectiveResolverPriorities()
    {
        // It must execute before anyone else!
        NestedFieldCacheControlDirectiveResolver::attach(AttachableExtensionGroups::DIRECTIVERESOLVERS, PHP_INT_MAX);
        // It must execute last!
        CacheControlDirectiveResolver::attach(AttachableExtensionGroups::DIRECTIVERESOLVERS, 0);
    }
}

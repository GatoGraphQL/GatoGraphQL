<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use GraphQLByPoP\GraphQLRequest\Component as GraphQLRequestComponent;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\CacheControl\Component as CacheControlComponent;

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
            \GraphQLByPoP\GraphQLRequest\Component::class,
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

    /**
     * Set the default component configuration
     *
     * @param array<string, mixed> $componentClassConfiguration
     */
    public static function customizeComponentClassConfiguration(
        array &$componentClassConfiguration
    ): void {
        // The mutation scheme can be set by param ?mutation_scheme=..., with values:
        // - "standard" => Use QueryRoot and MutationRoot
        // - "nested" => Use Root, and nested mutations with redundant root fields
        // - "lean_nested" => Use Root, and nested mutations without redundant root fields
        if (Environment::enableSettingMutationSchemeByURLParam()) {
            if ($mutationScheme = Request::getMutationScheme()) {
                $componentClassConfiguration[self::class][Environment::ENABLE_NESTED_MUTATIONS] = $mutationScheme != MutationSchemes::STANDARD;
                $componentClassConfiguration[EngineComponent::class][EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS] = $mutationScheme == MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS;
            }
        }
        // Enable GraphQL Introspection for PQL by doing ?enable_graphql_introspection=1
        if (Environment::enableEnablingGraphQLIntrospectionByURLParam()) {
            $enableGraphQLIntrospection = Request::enableGraphQLIntrospection();
            if ($enableGraphQLIntrospection !== null) {
                $componentClassConfiguration[self::class][Environment::ENABLE_GRAPHQL_INTROSPECTION] = $enableGraphQLIntrospection;
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
            self::initServices(dirname(__DIR__), '/Overrides');
            self::initSchemaServices(dirname(__DIR__), $skipSchema);

            // Boot conditionals
            if (
                class_exists(CacheControlComponent::class)
                && class_exists(AccessControlComponent::class)
                && AccessControlComponentConfiguration::canSchemaBePrivate()
            ) {
                self::initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses) || in_array(\PoP\AccessControl\Component::class, $skipSchemaComponentClasses),
                    '/ConditionalOnComponent/CacheControl/ConditionalOnComponent/AccessControl/ConditionalOnContext/PrivateSchema'
                );
            }
        }
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
    {
        if (self::isEnabled()) {
                self::initSystemServices(dirname(__DIR__));
        }
    }

    protected static function resolveEnabled(): bool
    {
        return GraphQLRequestComponent::isEnabled();
    }
}

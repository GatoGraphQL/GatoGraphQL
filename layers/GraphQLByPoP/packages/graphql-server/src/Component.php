<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLRequest\Component as GraphQLRequestComponent;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\BasicService\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;

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
    public function getDependedComponentClasses(): array
    {
        return [
            \GraphQLByPoP\GraphQLRequest\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
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
    public function customizeComponentClassConfiguration(
        array &$componentClassConfiguration
    ): void {
        // The mutation scheme can be set by param ?mutation_scheme=..., with values:
        // - "standard" => Use QueryRoot and MutationRoot
        // - "nested" => Use Root, and nested mutations with redundant root fields
        // - "lean_nested" => Use Root, and nested mutations without redundant root fields
        if (Environment::enableSettingMutationSchemeByURLParam()) {
            if ($mutationScheme = Request::getMutationScheme()) {
                $componentClassConfiguration[self::class][Environment::ENABLE_NESTED_MUTATIONS] = $mutationScheme !== MutationSchemes::STANDARD;
                $componentClassConfiguration[EngineComponent::class][EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS] = $mutationScheme === MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS;
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
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if ($this->isEnabled()) {
            $this->initServices(dirname(__DIR__));
            $this->initServices(dirname(__DIR__), '/Overrides');
            $this->initSchemaServices(dirname(__DIR__), $skipSchema);
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/Overrides');

            // Boot conditionals
            /** @var AccessControlComponentConfiguration */
            $componentConfiguration = App::getComponentManager()->getComponent(AccessControlComponent::class)->getConfiguration();
            if (
                class_exists(CacheControlComponent::class)
                && class_exists(AccessControlComponent::class)
                && $componentConfiguration->canSchemaBePrivate()
            ) {
                $this->initSchemaServices(
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
    protected function initializeSystemContainerServices(): void
    {
        if ($this->isEnabled()) {
            $this->initSystemServices(dirname(__DIR__));
        }
    }

    protected function resolveEnabled(): bool
    {
        return App::getComponentManager()->getComponent(GraphQLRequestComponent::class)->isEnabled();
    }
}

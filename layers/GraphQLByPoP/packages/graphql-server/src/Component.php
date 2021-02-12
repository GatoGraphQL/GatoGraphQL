<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use GraphQLByPoP\GraphQLServer\Conditional\AccessControl\ConditionalComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Root\Component\CanDisableComponentTrait;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Config\ServiceConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\Component as GraphQLRequestComponent;
use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\ComponentConfiguration as GraphQLRequestComponentConfiguration;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
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
            \GraphQLByPoP\GraphQLRequest\Component::class,
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
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if (self::isEnabled()) {
            parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
            ComponentConfiguration::setConfiguration($configuration);
            self::$COMPONENT_DIR = dirname(__DIR__);
            self::initYAMLServices(self::$COMPONENT_DIR);
            self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);

            // Boot conditional on having variables treated as expressions for @export directive
            if (GraphQLQueryComponentConfiguration::enableVariablesAsExpressions()) {
                self::maybeInitPHPSchemaServices(self::$COMPONENT_DIR, $skipSchema, '/ConditionalOnEnvironment/VariablesAsExpressions');
            }
            // Boot conditional on having embeddable fields
            if (APIComponentConfiguration::enableEmbeddableFields()) {
                self::maybeInitPHPSchemaServices(self::$COMPONENT_DIR, $skipSchema, '/ConditionalOnEnvironment/EmbeddableFields');
            }
            // The @export directive depends on the Multiple Query Execution being enabled
            if (GraphQLRequestComponentConfiguration::enableMultipleQueryExecution()) {
                self::maybeInitPHPSchemaServices(self::$COMPONENT_DIR, $skipSchema, '/ConditionalOnEnvironment/MultipleQueryExecution');
            }
            // Attach @removeIfNull?
            if (ComponentConfiguration::enableRemoveIfNullDirective()) {
                self::maybeInitPHPSchemaServices(self::$COMPONENT_DIR, $skipSchema, '/ConditionalOnEnvironment/RemoveIfNull');
            }
            if (class_exists('\PoP\AccessControl\Component')) {
                ConditionalComponent::initialize(
                    $configuration,
                    $skipSchema
                );
            }
            ServiceConfiguration::initialize();
        }
    }

    protected static function resolveEnabled()
    {
        return GraphQLRequestComponent::isEnabled();
    }
}

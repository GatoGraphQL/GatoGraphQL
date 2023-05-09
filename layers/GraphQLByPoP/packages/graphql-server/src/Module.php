<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use PoP\AccessControl\Module as AccessControlModule;
use PoP\AccessControl\ModuleConfiguration as AccessControlModuleConfiguration;
use PoP\CacheControl\Module as CacheControlModule;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Engine\Module as EngineModule;
use PoP\Root\App;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \GraphQLByPoP\GraphQLRequest\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            AccessControlModule::class,
            CacheControlModule::class,
        ];
    }

    /**
     * Set the default module configuration
     *
     * @param array<string,mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void {
        parent::customizeModuleClassConfiguration($moduleClassConfiguration);

        /**
         * The mutation scheme can be set by param ?mutation_scheme=..., with values:
         *
         *   - "standard" => Use QueryRoot and MutationRoot
         *   - "nested" => Use Root, and nested mutations with redundant root fields
         *   - "lean_nested" => Use Root, and nested mutations without redundant root fields
         */
        $mutationScheme = Request::getMutationScheme();
        if ($mutationScheme !== null) {
            $moduleClassConfiguration[self::class][Environment::ENABLE_NESTED_MUTATIONS] = $mutationScheme !== MutationSchemes::STANDARD;
            $moduleClassConfiguration[EngineModule::class][EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS] = $mutationScheme === MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS;
        }

        // Enable GraphQL Introspection for PQL by doing ?enable_graphql_introspection=1
        $enableGraphQLIntrospection = Request::enableGraphQLIntrospection();
        if ($enableGraphQLIntrospection !== null) {
            $moduleClassConfiguration[self::class][Environment::ENABLE_GRAPHQL_INTROSPECTION] = $enableGraphQLIntrospection;
        }
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__));
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        // Boot conditionals
        try {
            if (class_exists(AccessControlModule::class) && App::getModule(AccessControlModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/AccessControl/Overrides');
            }
        } catch (ComponentNotExistsException) {
        }

        try {
            if (class_exists(CacheControlModule::class) && App::getModule(CacheControlModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/CacheControl/Overrides');
            }
        } catch (ComponentNotExistsException) {
        }

        try {
            if (class_exists(AccessControlModule::class) && App::getModule(AccessControlModule::class)->isEnabled()) {
                /** @var AccessControlModuleConfiguration */
                $moduleConfiguration = App::getModule(AccessControlModule::class)->getConfiguration();
                try {
                    if (
                        class_exists(CacheControlModule::class)
                        && App::getModule(CacheControlModule::class)->isEnabled()
                        && $moduleConfiguration->canSchemaBePrivate()
                    ) {
                        $this->initSchemaServices(
                            dirname(__DIR__),
                            $skipSchema || in_array(CacheControlModule::class, $skipSchemaModuleClasses) || in_array(AccessControlModule::class, $skipSchemaModuleClasses),
                            '/ConditionalOnModule/CacheControl/ConditionalOnModule/AccessControl/ConditionalOnContext/PrivateSchema'
                        );
                    }
                } catch (ComponentNotExistsException) {
                }
            }
        } catch (ComponentNotExistsException) {
        }
    }
}

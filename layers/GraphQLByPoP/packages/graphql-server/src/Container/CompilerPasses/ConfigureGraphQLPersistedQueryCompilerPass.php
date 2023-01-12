<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Container\CompilerPasses;

use PoP\Root\App;
use PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Root\Facades\Translation\SystemTranslationAPIFacade;

class ConfigureGraphQLPersistedQueryCompilerPass extends AbstractCompilerPass
{
    protected function enabled(): bool
    {
        // If any downstream Module is disabled, its ModuleConfiguration will be null
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration === null) {
            return false;
        }
        /** @var ModuleConfiguration $moduleConfiguration */
        return $moduleConfiguration->addGraphQLIntrospectionPersistedQuery();
    }

    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $introspectionPersistedQuery = <<<EOT
        query IntrospectionQuery {
            __schema {
                queryType {
                    name
                }
                mutationType {
                    name
                }
                subscriptionType {
                    name
                }
                types {
                    ...FullType
                }
                directives {
                    name
                    description
                    locations
                    args {
                        ...InputValue
                    }
                }
            }
        }

        fragment FullType on __Type {
            kind
            name
            description
            fields(includeDeprecated: true) {
                name
                description
                args {
                    ...InputValue
                }
                type {
                    ...TypeRef
                }
                isDeprecated
                deprecationReason
            }
            inputFields {
                ...InputValue
            }
            interfaces {
                ...TypeRef
            }
            enumValues(includeDeprecated: true) {
                name
                description
                isDeprecated
                deprecationReason
            }
            possibleTypes {
                ...TypeRef
            }
        }

        fragment InputValue on __InputValue {
            name
            description
            type {
                ...TypeRef
            }
            defaultValue
        }

        fragment TypeRef on __Type {
            kind
            name
            ofType {
                kind
                name
                ofType {
                    kind
                    name
                    ofType {
                        kind
                        name
                        ofType {
                            kind
                            name
                            ofType {
                                kind
                                name
                                ofType {
                                    kind
                                    name
                                    ofType {
                                        kind
                                        name
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        EOT;
        /**
         * Watch out: in the Service Configuration we can't access other services yet,
         * so we use the Translate service from the System Container
         */
        $translationAPI = SystemTranslationAPIFacade::getInstance();
        $description = $translationAPI->__('GraphQL introspection query', 'graphql-server');
        $persistedQueryManagerDefinition = $containerBuilderWrapper->getDefinition(PersistedQueryManagerInterface::class);
        $persistedQueryManagerDefinition->addMethodCall(
            'addPersistedQuery',
            [
                'introspectionQuery',
                $introspectionPersistedQuery,
                $description
            ]
        );
    }
}

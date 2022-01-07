<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Container\CompilerPasses;

use PoP\Root\App;
use GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Translation\Facades\SystemTranslationAPIFacade;

class ConfigureGraphQLPersistedQueryCompilerPass extends AbstractCompilerPass
{
    protected function enabled(): bool
    {
        // If any downstream Component is disabled, its ComponentConfiguration will be null
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration === null) {
            return false;
        }
        /** @var ComponentConfiguration $componentConfiguration */
        return $componentConfiguration->addGraphQLIntrospectionPersistedQuery();
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
        $graphQLPersistedQueryManagerDefinition = $containerBuilderWrapper->getDefinition(GraphQLPersistedQueryManagerInterface::class);
        $graphQLPersistedQueryManagerDefinition->addMethodCall(
            'addPersistedQuery',
            [
                'introspectionQuery',
                $introspectionPersistedQuery,
                $description
            ]
        );
    }
}

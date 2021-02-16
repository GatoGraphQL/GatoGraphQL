<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Config;

use GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\Translation\Facades\SystemTranslationAPIFacade;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        /**
         * Override class GraphQLDataStructureFormatter from GraphQLAPI
         */
        ContainerBuilderUtils::injectServicesIntoService(
            DataStructureManagerInterface::class,
            'GraphQLByPoP\\GraphQLServer\\DataStructureFormatters',
            'add'
        );

        // Add RouteModuleProcessors to the Manager
        ContainerBuilderUtils::injectServicesIntoService(
            RouteModuleProcessorManagerInterface::class,
            'GraphQLByPoP\\GraphQLServer\\RouteModuleProcessors',
            'add'
        );

        /**
         * GraphQL persisted query for Introspection query
         */
        if (Environment::addGraphQLIntrospectionPersistedQuery()) {
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
            $description = $translationAPI->__('GraphQL introspection query', 'examples-for-pop');
            // Inject the values into the service
            ContainerBuilderUtils::injectValuesIntoService(
                GraphQLPersistedQueryManagerInterface::class,
                'add',
                'introspectionQuery',
                $introspectionPersistedQuery,
                $description
            );
        }
    }
}

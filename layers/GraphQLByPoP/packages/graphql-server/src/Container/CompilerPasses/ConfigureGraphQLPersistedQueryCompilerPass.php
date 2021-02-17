<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Container\CompilerPasses;

use GraphQLByPoP\GraphQLServer\Environment;
use GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface;
use PoP\Translation\Facades\SystemTranslationAPIFacade;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigureGraphQLPersistedQueryCompilerPass implements CompilerPassInterface
{
    /**
     * GraphQL persisted query for Introspection query
     */
    public function process(ContainerBuilder $containerBuilder): void
    {
        if (!Environment::addGraphQLIntrospectionPersistedQuery()) {
            return;
        }

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
        $graphQLPersistedQueryManagerDefinition = $containerBuilder->getDefinition(GraphQLPersistedQueryManagerInterface::class);
        $graphQLPersistedQueryManagerDefinition->addMethodCall(
            'add',
            [
                'introspectionQuery',
                $introspectionPersistedQuery,
                $description
            ]
        );
    }
}

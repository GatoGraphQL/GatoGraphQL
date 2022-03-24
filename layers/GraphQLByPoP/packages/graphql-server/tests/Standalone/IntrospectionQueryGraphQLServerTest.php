<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

class IntrospectionQueryGraphQLServerTest extends AbstractGraphQLServerTestCase
{
    public function testIntrospectionQuery(): void
    {
        $this->assertGraphQLQueryExecution(
            '
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
            ',
            [
                "data" => [
                    "__schema" => [
                        "queryType" => [
                            "name" => "QueryRoot"
                        ],
                        "mutationType" => [
                            "name" => "MutationRoot"
                        ],
                        "subscriptionType" => null,
                        "types" => [
                            [
                                "kind" => "OBJECT",
                                "name" => "MutationRoot",
                                "description" => "Mutation type, starting from which mutations are executed",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "QueryRoot",
                                "description" => "Query type, starting from which the query is executed",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "Root",
                                "description" => "Root type, starting from which the query is executed",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "mutationRoot",
                                        "description" => "Get the Mutation Root type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "OBJECT",
                                            "name" => "MutationRoot",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "queryRoot",
                                        "description" => "Get the Query Root type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "OBJECT",
                                            "name" => "QueryRoot",
                                            "ofType" => null
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "_DirectiveExtensions",
                                "description" => "Extensions (custom metadata) added to the directive",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "needsDataToExecute",
                                        "description" => "If no objects are returned in the field (eg: because they failed validation), does the directive still need to be executed?",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "_EnumValueExtensions",
                                "description" => "Extensions (custom metadata) added to the enum value",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isAdminElement",
                                        "description" => "Is this element considered an 'admin' element in the schema? (If so, it is only exposed in the schema when 'Expose admin elements' is enabled)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "_FieldExtensions",
                                "description" => "Extensions (custom metadata) added to the field",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isAdminElement",
                                        "description" => "Is this element considered an 'admin' element in the schema? (If so, it is only exposed in the schema when 'Expose admin elements' is enabled)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isMutation",
                                        "description" => "Is this a mutation field? Particularly required when doing 'nested mutations' (where mutation fields can be present on any type, not only on `MutationRoot`)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "_InputValueExtensions",
                                "description" => "Extensions (custom metadata) added to the input value",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isAdminElement",
                                        "description" => "Is this element considered an 'admin' element in the schema? (If so, it is only exposed in the schema when 'Expose admin elements' is enabled)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "_NamedTypeExtensions",
                                "description" => "Extensions (custom metadata) added to the GraphQL type (for all 'named' types: Object, Interface, Union, Scalar, Enum and InputObject)",
                                "fields" => [
                                    [
                                        "name" => "elementName",
                                        "description" => "The type's non-namespaced name",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "String!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "String",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "namespacedName",
                                        "description" => "The type's namespaced name",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "String!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "String",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "_SchemaExtensions",
                                "description" => "Extensions (custom metadata) added to the GraphQL schema",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isNamespaced",
                                        "description" => "Is the schema namespaced?",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "__Directive",
                                "description" => "A GraphQL directive in the data graph",
                                "fields" => [
                                    [
                                        "name" => "args",
                                        "description" => "Directive's arguments",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "[__InputValue!]!",
                                            "ofType" => [
                                                "kind" => "LIST",
                                                "name" => "[__InputValue!]",
                                                "ofType" => [
                                                    "kind" => "NON_NULL",
                                                    "name" => "__InputValue!",
                                                    "ofType" => [
                                                        "kind" => "OBJECT",
                                                        "name" => "__InputValue",
                                                        "ofType" => null
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "description",
                                        "description" => "Directive's description",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "extensions",
                                        "description" => "Extensions (custom metadata) added to the directive (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "_DirectiveExtensions!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "_DirectiveExtensions",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isRepeatable",
                                        "description" => "Can the directive be executed more than once in the same field?",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "locations",
                                        "description" => "The locations where the directive may be placed",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "[DirectiveLocation!]!",
                                            "ofType" => [
                                                "kind" => "LIST",
                                                "name" => "[DirectiveLocation!]",
                                                "ofType" => [
                                                    "kind" => "NON_NULL",
                                                    "name" => "DirectiveLocation!",
                                                    "ofType" => [
                                                        "kind" => "ENUM",
                                                        "name" => "DirectiveLocation",
                                                        "ofType" => null
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "name",
                                        "description" => "Directive's name",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "String!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "String",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "__EnumValue",
                                "description" => "Representation of an Enum value in GraphQL",
                                "fields" => [
                                    [
                                        "name" => "deprecationReason",
                                        "description" => "Why was the enum value deprecated?",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "description",
                                        "description" => "Enum value's description as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACyBIC1BHnjL)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "extensions",
                                        "description" => "Extensions (custom metadata) added to the enum value (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "_EnumValueExtensions!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "_EnumValueExtensions",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isDeprecated",
                                        "description" => "Is the enum value deprecated?",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "name",
                                        "description" => "Enum value's name as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACvBBCyBH6rd)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "String!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "String",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "__Field",
                                "description" => "Representation of a GraphQL type's field",
                                "fields" => [
                                    [
                                        "name" => "args",
                                        "description" => "Field arguments",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "[__InputValue!]!",
                                            "ofType" => [
                                                "kind" => "LIST",
                                                "name" => "[__InputValue!]",
                                                "ofType" => [
                                                    "kind" => "NON_NULL",
                                                    "name" => "__InputValue!",
                                                    "ofType" => [
                                                        "kind" => "OBJECT",
                                                        "name" => "__InputValue",
                                                        "ofType" => null
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "deprecationReason",
                                        "description" => "Why was the field deprecated?",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "description",
                                        "description" => "Field's description",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "extensions",
                                        "description" => "Extensions (custom metadata) added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "_FieldExtensions!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "_FieldExtensions",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "isDeprecated",
                                        "description" => "Is the field deprecated?",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "Boolean!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "Boolean",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "name",
                                        "description" => "Field's name",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "String!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "String",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "type",
                                        "description" => "Type to which the field belongs",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "__Type!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "__Type",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "__InputValue",
                                "description" => "Representation of an input object in GraphQL",
                                "fields" => [
                                    [
                                        "name" => "defaultValue",
                                        "description" => "Default value of the input value",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "description",
                                        "description" => "Input value's description",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "extensions",
                                        "description" => "Extensions (custom metadata) added to the input (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "_InputValueExtensions!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "_InputValueExtensions",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "name",
                                        "description" => "Input value's name as defined by the GraphQL spec",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "String!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "String",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "type",
                                        "description" => "Type of the input value",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "__Type!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "__Type",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "__Schema",
                                "description" => "Schema type, to implement the introspection fields",
                                "fields" => [
                                    [
                                        "name" => "directives",
                                        "description" => "All directives registered in the data graph",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [
                                            [
                                                "name" => "ofKinds",
                                                "description" => "Include only directives of provided types",
                                                "defaultValue" => null,
                                                "type" => [
                                                    "kind" => "LIST",
                                                    "name" => "[DirectiveKindEnum]",
                                                    "ofType" => [
                                                        "kind" => "ENUM",
                                                        "name" => "DirectiveKindEnum",
                                                        "ofType" => null
                                                    ]
                                                ]
                                            ]
                                        ],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "[__Directive!]!",
                                            "ofType" => [
                                                "kind" => "LIST",
                                                "name" => "[__Directive!]",
                                                "ofType" => [
                                                    "kind" => "NON_NULL",
                                                    "name" => "__Directive!",
                                                    "ofType" => [
                                                        "kind" => "OBJECT",
                                                        "name" => "__Directive",
                                                        "ofType" => null
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "extensions",
                                        "description" => "Extensions (custom metadata) added to the GraphQL schema",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "_SchemaExtensions!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "_SchemaExtensions",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "mutationType",
                                        "description" => "The type, accessible from the root, that resolves mutations",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "OBJECT",
                                            "name" => "__Type",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "queryType",
                                        "description" => "The type, accessible from the root, that resolves queries",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "__Type!",
                                            "ofType" => [
                                                "kind" => "OBJECT",
                                                "name" => "__Type",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "subscriptionType",
                                        "description" => "The type, accessible from the root, that resolves subscriptions",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "OBJECT",
                                            "name" => "__Type",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "type",
                                        "description" => "Obtain a specific type from the schema",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [
                                            [
                                                "name" => "name",
                                                "description" => "The name of the type",
                                                "defaultValue" => null,
                                                "type" => [
                                                    "kind" => "SCALAR",
                                                    "name" => "String",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ],
                                        "type" => [
                                            "kind" => "OBJECT",
                                            "name" => "__Type",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "types",
                                        "description" => "All types registered in the data graph",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "[__Type!]!",
                                            "ofType" => [
                                                "kind" => "LIST",
                                                "name" => "[__Type!]",
                                                "ofType" => [
                                                    "kind" => "NON_NULL",
                                                    "name" => "__Type!",
                                                    "ofType" => [
                                                        "kind" => "OBJECT",
                                                        "name" => "__Type",
                                                        "ofType" => null
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "OBJECT",
                                "name" => "__Type",
                                "description" => "Representation of each GraphQL type in the graph",
                                "fields" => [
                                    [
                                        "name" => "description",
                                        "description" => "Type's description as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACyBIC1BHnjL)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "enumValues",
                                        "description" => "Type's enum values (available for Enum type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC9CDD_CAA2lB)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [
                                            [
                                                "name" => "includeDeprecated",
                                                "description" => "Include deprecated fields?",
                                                "defaultValue" => null,
                                                "type" => [
                                                    "kind" => "SCALAR",
                                                    "name" => "Boolean",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ],
                                        "type" => [
                                            "kind" => "LIST",
                                            "name" => "[__EnumValue!]",
                                            "ofType" => [
                                                "kind" => "NON_NULL",
                                                "name" => "__EnumValue!",
                                                "ofType" => [
                                                    "kind" => "OBJECT",
                                                    "name" => "__EnumValue",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "extensions",
                                        "description" => "Extensions (custom metadata) added to the GraphQL type (for all 'named' types: Object, Interface, Union, Scalar, Enum and InputObject) (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "OBJECT",
                                            "name" => "_NamedTypeExtensions",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "fields",
                                        "description" => "Type's fields (available for Object and Interface types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC3BBCnCA8pY)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [
                                            [
                                                "name" => "includeDeprecated",
                                                "description" => "Include deprecated fields?",
                                                "defaultValue" => null,
                                                "type" => [
                                                    "kind" => "SCALAR",
                                                    "name" => "Boolean",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ],
                                        "type" => [
                                            "kind" => "LIST",
                                            "name" => "[__Field!]",
                                            "ofType" => [
                                                "kind" => "NON_NULL",
                                                "name" => "__Field!",
                                                "ofType" => [
                                                    "kind" => "OBJECT",
                                                    "name" => "__Field",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "inputFields",
                                        "description" => "Type's input Fields (available for InputObject type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLAuDABCBIu9N)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "LIST",
                                            "name" => "[__InputValue!]",
                                            "ofType" => [
                                                "kind" => "NON_NULL",
                                                "name" => "__InputValue!",
                                                "ofType" => [
                                                    "kind" => "OBJECT",
                                                    "name" => "__InputValue",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "interfaces",
                                        "description" => "Type's interfaces (available for Object type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACpCBCxCA7tB)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "LIST",
                                            "name" => "[__Type!]",
                                            "ofType" => [
                                                "kind" => "NON_NULL",
                                                "name" => "__Type!",
                                                "ofType" => [
                                                    "kind" => "OBJECT",
                                                    "name" => "__Type",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "kind",
                                        "description" => "Type's kind as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACqBBCvBAtrC)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "TypeKind!",
                                            "ofType" => [
                                                "kind" => "ENUM",
                                                "name" => "TypeKind",
                                                "ofType" => null
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "name",
                                        "description" => "Type's name as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACvBBCyBH6rd)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "ofType",
                                        "description" => "The type of the nested type (available for NonNull and List types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLA4DABCBIu9N)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "OBJECT",
                                            "name" => "__Type",
                                            "ofType" => null
                                        ]
                                    ],
                                    [
                                        "name" => "possibleTypes",
                                        "description" => "Type's possible types (available for Interface and Union types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACzCBC7CA0vN)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "LIST",
                                            "name" => "[__Type!]",
                                            "ofType" => [
                                                "kind" => "NON_NULL",
                                                "name" => "__Type!",
                                                "ofType" => [
                                                    "kind" => "OBJECT",
                                                    "name" => "__Type",
                                                    "ofType" => null
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "name" => "specifiedByURL",
                                        "description" => "A scalar specification URL (a String (in the form of a URL) for custom scalars, otherwise must be null) as defined by the GraphQL spec (https://spec.graphql.org/draft/#sel-IAJXNFA0EABABL9N)",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "String",
                                            "ofType" => null
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [
                                    [
                                        "kind" => "INTERFACE",
                                        "name" => "Node",
                                        "ofType" => null
                                    ]
                                ],
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "SCALAR",
                                "name" => "Boolean",
                                "description" => "The Boolean scalar type represents `true` or `false`.",
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "SCALAR",
                                "name" => "DangerouslyDynamic",
                                "description" => "Special scalar type which is not coerced or validated. In particular, it does not need to validate if it is an array or not, as GraphQL requires based on the applied WrappingType (such as `[String]`).",
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "SCALAR",
                                "name" => "ID",
                                "description" => "The ID scalar type represents a unique identifier.",
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "SCALAR",
                                "name" => "Int",
                                "description" => "The Int scalar type represents non-fractional signed whole numeric values.",
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            // [
                            //     "description" => "Custom scalar representing a JSON Object of unrestricted shape",
                            //     "enumValues" => null,
                            //     "fields" => null,
                            //     "inputFields" => null,
                            //     "interfaces" => null,
                            //     "kind" => "SCALAR",
                            //     "name" => "JSONObject",
                            //     "possibleTypes" => null
                            // ],
                            [
                                "kind" => "SCALAR",
                                "name" => "String",
                                "description" => "The String scalar type represents textual data, represented as UTF-8 character sequences.",
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => null,
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "INTERFACE",
                                "name" => "Node",
                                "description" => "The fundamental fields that must be implemented by all objects",
                                "fields" => [
                                    [
                                        "name" => "id",
                                        "description" => "The object's unique identifier for its type",
                                        "isDeprecated" => false,
                                        "deprecationReason" => null,
                                        "args" => [],
                                        "type" => [
                                            "kind" => "NON_NULL",
                                            "name" => "ID!",
                                            "ofType" => [
                                                "kind" => "SCALAR",
                                                "name" => "ID",
                                                "ofType" => null
                                            ]
                                        ]
                                    ]
                                ],
                                "inputFields" => null,
                                "interfaces" => [],
                                "enumValues" => null,
                                "possibleTypes" => [
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "Root",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "__Type",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "_NamedTypeExtensions",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "__InputValue",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "_InputValueExtensions",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "__EnumValue",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "_EnumValueExtensions",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "__Field",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "_FieldExtensions",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "__Schema",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "_SchemaExtensions",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "__Directive",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "_DirectiveExtensions",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "MutationRoot",
                                        "ofType" => null
                                    ],
                                    [
                                        "kind" => "OBJECT",
                                        "name" => "QueryRoot",
                                        "ofType" => null
                                    ]
                                ]
                            ],
                            [
                                "kind" => "ENUM",
                                "name" => "DirectiveKindEnum",
                                "description" => null,
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => [
                                    [
                                        "name" => "query",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "schema",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ]
                                ],
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "ENUM",
                                "name" => "DirectiveLocation",
                                "description" => null,
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => [
                                    [
                                        "name" => "ARGUMENT_DEFINITION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "ENUM",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "ENUM_VALUE",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "FIELD",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "FIELD_DEFINITION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "FRAGMENT_DEFINITION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "FRAGMENT_SPREAD",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "INLINE_FRAGMENT",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "INPUT_FIELD_DEFINITION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "INPUT_OBJECT",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "INTERFACE",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "MUTATION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "OBJECT",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "QUERY",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "SCALAR",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "SCHEMA",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "SUBSCRIPTION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "UNION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ]
                                ],
                                "possibleTypes" => null
                            ],
                            [
                                "kind" => "ENUM",
                                "name" => "TypeKind",
                                "description" => null,
                                "fields" => null,
                                "inputFields" => null,
                                "interfaces" => null,
                                "enumValues" => [
                                    [
                                        "name" => "ENUM",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "INPUT_OBJECT",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "INTERFACE",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "LIST",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "NON_NULL",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "OBJECT",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "SCALAR",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ],
                                    [
                                        "name" => "UNION",
                                        "description" => null,
                                        "isDeprecated" => false,
                                        "deprecationReason" => null
                                    ]
                                ],
                                "possibleTypes" => null
                            ]
                        ],
                        "directives" => [
                            [
                                "name" => "cacheControl",
                                "description" => "HTTP caching (https://tools.ietf.org/html/rfc7234): Cache the response by setting a Cache-Control header with a max-age value; this value is calculated as the minimum max-age value among all requested fields. If any field has max-age: 0, a corresponding 'no-store' value is sent, indicating to not cache the response",
                                "locations" => [
                                    "FIELD_DEFINITION"
                                ],
                                "args" => [
                                    [
                                        "name" => "maxAge",
                                        "description" => "Use a specific max-age value for the field, instead of the one configured in the directive",
                                        "defaultValue" => null,
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "Int",
                                            "ofType" => null
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "name" => "disableAccess",
                                "description" => "It disables access to the field",
                                "locations" => [
                                    "FIELD_DEFINITION"
                                ],
                                "args" => []
                            ],
                            [
                                "name" => "disableAccessForDirectives",
                                "description" => "It disables access to the field",
                                "locations" => [
                                    "FIELD_DEFINITION"
                                ],
                                "args" => []
                            ],
                            [
                                "name" => "include",
                                "description" => "Include the field value in the output only if the argument 'if' evals to `true`",
                                "locations" => [
                                    "FIELD",
                                    "FRAGMENT_SPREAD",
                                    "INLINE_FRAGMENT"
                                ],
                                "args" => [
                                    [
                                        "name" => "if",
                                        "description" => "Argument that must evaluate to `true` to include the field value in the output",
                                        "defaultValue" => null,
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "Boolean",
                                            "ofType" => null
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "name" => "skip",
                                "description" => "Include the field value in the output only if the argument 'if' evals to `false`",
                                "locations" => [
                                    "FIELD",
                                    "FRAGMENT_SPREAD",
                                    "INLINE_FRAGMENT"
                                ],
                                "args" => [
                                    [
                                        "name" => "if",
                                        "description" => "Argument that must evaluate to `false` to include the field value in the output",
                                        "defaultValue" => null,
                                        "type" => [
                                            "kind" => "SCALAR",
                                            "name" => "Boolean",
                                            "ofType" => null
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
    }
}

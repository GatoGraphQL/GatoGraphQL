<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

class SchemaDefinition
{
    // Field/Directive Argument Names
    const ARGNAME_NAME = 'name';
    const ARGNAME_NAMESPACED_NAME = 'namespacedName';
    const ARGNAME_ELEMENT_NAME = 'elementName';
    const ARGNAME_TYPE = 'type';
    const ARGNAME_NON_NULLABLE = 'nonNullable';
    const ARGNAME_IS_ARRAY = 'isArray';
    const ARGNAME_NON_EMPTY_ARRAY = 'nonEmptyArray';
    const ARGNAME_REFERENCED_TYPE = 'referencedType';
    const ARGNAME_DESCRIPTION = 'description';
    const ARGNAME_VERSION = 'version';
    const ARGNAME_VERSION_CONSTRAINT = 'versionConstraint';
    const ARGNAME_MANDATORY = 'mandatory';
    const ARGNAME_INPUT_OBJECT_NAME = 'inputObjectName';
    const ARGNAME_ENUM_NAME = 'enumName';
    const ARGNAME_ENUM_VALUES = 'enumValues';
    const ARGNAME_DEPRECATED = 'deprecated';
    const ARGNAME_DEPRECATIONDESCRIPTION = 'deprecationDescription';
    const ARGNAME_DEFAULT_VALUE = 'defaultValue';
    const ARGNAME_ARGS = 'args';
    const ARGNAME_RESULTS_IMPLEMENT_INTERFACE = 'resultsImplementInterface';
    const ARGNAME_INTERFACES = 'interfaces';
    const ARGNAME_RELATIONAL = 'relational';
    const ARGNAME_FIELDS = 'fields';
    const ARGNAME_CONNECTIONS = 'connections';
    const ARGNAME_GLOBAL_CONNECTIONS = 'globalConnections';
    const ARGNAME_GLOBAL_FIELDS = 'globalFields';
    const ARGNAME_QUERY_TYPE = 'queryType';
    const ARGNAME_TYPES = 'types';
    const ARGNAME_TYPE_SCHEMA = 'typeSchema';
    const ARGNAME_POSSIBLE_TYPES = 'possibleTypes';
    const ARGNAME_BASERESOLVER = 'baseResolver';
    const ARGNAME_RECURSION = 'recursion';
    const ARGNAME_REPEATED = 'repeated';
    const ARGNAME_IS_UNION = 'isUnion';
    const ARGNAME_DIRECTIVES = 'directives';
    const ARGNAME_GLOBAL_DIRECTIVES = 'globalDirectives';
    const ARGNAME_FIELD_IS_MUTATION = 'isMutation';
    const ARGNAME_DIRECTIVE_TYPE = 'directiveType';
    const ARGNAME_DIRECTIVE_PIPELINE_POSITION = 'pipelinePosition';
    const ARGNAME_DIRECTIVE_IS_REPEATABLE = 'isRepeatable';
    const ARGNAME_DIRECTIVE_NEEDS_DATA_TO_EXECUTE = 'needsDataToExecute';
    const ARGNAME_DIRECTIVE_LIMITED_TO_FIELDS = 'limitedToFields';
    const ARGNAME_DIRECTIVE_EXPRESSIONS = 'expressions';

    const ARGVALUE_SCHEMA_SHAPE_FLAT = 'flat';
    const ARGVALUE_SCHEMA_SHAPE_NESTED = 'nested';

    // Field/Directive Argument Types

    /**
     * Custom scalar type "comprising" the 5 built-in scalar types by GraphQL:
     * 
     * - String
     * - Int
     * - Float
     * - Bool
     * - ID
     * 
     * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
     * 
     * To be more correct, the name should be `ANY_BUILT_IN_SCALAR`,
     * but `ANY_SCALAR` is used instead for convenience.
     * 
     * This type is a hack, to address a defficiency in the GraphQL spec:
     * In GraphQL there is no union of scalars.
     * 
     * @see https://github.com/graphql/graphql-spec/issues/215
     * 
     * This type comes to represent any of the built-in scalars, to be used
     * when we do not know of what actual scalar type will the value be.
     * 
     * Eg: when calling `get_option` or `get_post_meta` in WordPress,
     * which may return a bool, or int, or string. The developer will know,
     * but the schema doesn't know.
     * 
     * In GraphQL clients (such as GraphiQL), errors will be shown
     * when providing a `String` to an input of type `ANY_SCALAR`,
     * but the GraphQL server will process the value correctly.
     */
    const TYPE_ANY_SCALAR = 'any_scalar';

    /**
     * Custom scalar type representing an `object` from PHP:
     * some instance from a class or stdClass.
     * 
     * It also represents a JSONObject input.
     * 
     * Please notice: this type is not an `array`, however it can be represented
     * through an array on the server-side via PHP. The distinction is important,
     * because an `array` is not a type in GraphQL, but an `object` can be, as a custom scalar
     */
    const TYPE_OBJECT = 'object';

    /**
     * This custom scalar type comprises the 5 built-in scalar types by GraphQL
     * (represented also by `ANY_SCALAR`), plus the Object type
     * (represented also by `OBJECT`), plus any custom scalar
     * (such as Date, or any other).
     * 
     * Because it comprises the Object type, this custom scalar type can
     * also be represented through an array on the server-side via PHP.
     */
    const TYPE_MIXED = 'mixed';

    /**
     * This custom scalar type comprises the 2 atomic types by GraphQL:
     * 
     * - String
     * - Int
     * 
     * It is used to represent array keys, which can only be numeric or strings.
     */
    const TYPE_ARRAY_KEY = 'array_key';

    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_STRING = 'string';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_INT = 'int';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_FLOAT = 'float';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_BOOL = 'bool';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_ID = 'id';

    /**
     * Custom scalars
     */
    const TYPE_DATE = 'date';
    const TYPE_TIME = 'time';
    const TYPE_URL = 'url';
    const TYPE_EMAIL = 'email';
    const TYPE_IP = 'ip';
    const TYPE_ENUM = 'enum';
    const TYPE_INPUT_OBJECT = 'input_object';

    /**
     * Using "/" doesn't work with GraphQL Voyager!
     */
    const TOKEN_NAMESPACE_SEPARATOR = '_';
}

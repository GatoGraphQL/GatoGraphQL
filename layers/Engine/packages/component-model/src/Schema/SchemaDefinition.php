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
     * Custom scalar type "comprising" the 5 atomic scalar types by GraphQL:
     * 
     * - String
     * - Int
     * - Float
     * - Bool
     * - ID
     * 
     * In GraphQL there is no union of scalars, hence this type comes to represent
     * any of all the scalars. It can be used when we cannot know of what type will
     * the value be. Eg: when calling `get_option` or `get_post_meta` in WordPress.
     * 
     * In GraphQL clients, errors will be shown when providing a `String` to an input
     * of type `ANY_SCALAR`, but the GraphQL server will process the value correctly.
     * 
     * @see https://spec.graphql.org/draft/#sec-Scalars
     * @see https://github.com/graphql/graphql-spec/issues/215
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
     * This custom scalar type comprises the 5 atomic types by GraphQL, plus the Object.
     * 
     * As a consequence, this type can also be represented through an array
     * on the server-side via PHP
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

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
    const ARGNAME_MAY_BE_ARRAY = 'mayBeArray';
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
    const TYPE_MIXED = 'mixed';
    const TYPE_ID = 'id';
    const TYPE_STRING = 'string';
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOL = 'bool';
    const TYPE_DATE = 'date';
    const TYPE_TIME = 'time';
    const TYPE_OBJECT = 'object';
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

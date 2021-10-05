<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

class SchemaDefinition
{
    const NAME = 'name';
    const NAMESPACED_NAME = 'namespacedName';
    const ELEMENT_NAME = 'elementName';
    const TYPE_RESOLVER = 'typeResolver';
    const TYPE_NAME = 'typeName';
    const NON_NULLABLE = 'nonNullable';
    const IS_ARRAY = 'isArray';
    const IS_NON_NULLABLE_ITEMS_IN_ARRAY = 'isNonNullableItemsInArray';
    const IS_ARRAY_OF_ARRAYS = 'isArrayOfArrays';
    const IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS = 'isNonNullableItemsInArrayOfArrays';
    const REFERENCED_TYPE = 'referencedType';
    const DESCRIPTION = 'description';
    const VERSION = 'version';
    const VERSION_CONSTRAINT = 'versionConstraint';
    const MANDATORY = 'mandatory';
    const INPUT_OBJECT_NAME = 'inputObjectName';
    const ENUM_NAME = 'enumName';
    const ENUM_VALUES = 'enumValues';
    const DEPRECATED = 'deprecated';
    const DEPRECATIONDESCRIPTION = 'deprecationDescription';
    const DEFAULT_VALUE = 'defaultValue';
    const ARGS = 'args';
    const RESULTS_IMPLEMENT_INTERFACE = 'resultsImplementInterface';
    const INTERFACES = 'interfaces';
    const RELATIONAL = 'relational';
    const FIELDS = 'fields';
    const CONNECTIONS = 'connections';
    const GLOBAL_CONNECTIONS = 'globalConnections';
    const GLOBAL_FIELDS = 'globalFields';
    const QUERY_TYPE = 'queryType';
    const TYPES = 'types';
    const TYPE_SCHEMA = 'typeSchema';
    const POSSIBLE_TYPES = 'possibleTypes';
    const BASERESOLVER = 'baseResolver';
    const RECURSION = 'recursion';
    const REPEATED = 'repeated';
    const IS_UNION = 'isUnion';
    const DIRECTIVES = 'directives';
    const GLOBAL_DIRECTIVES = 'globalDirectives';
    const FIELD_IS_MUTATION = 'isMutation';
    const DIRECTIVE_TYPE = 'directiveType';
    const DIRECTIVE_PIPELINE_POSITION = 'pipelinePosition';
    const DIRECTIVE_IS_REPEATABLE = 'isRepeatable';
    const DIRECTIVE_NEEDS_DATA_TO_EXECUTE = 'needsDataToExecute';
    const DIRECTIVE_LIMITED_TO_FIELDS = 'limitedToFields';
    const DIRECTIVE_EXPRESSIONS = 'expressions';
}

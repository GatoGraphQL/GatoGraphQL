<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

class SchemaDefinition
{
    public const NAME = 'name';
    public const NAMESPACED_NAME = 'namespacedName';
    public const ELEMENT_NAME = 'elementName';
    public const TYPE_RESOLVER = 'typeResolver';
    public const NON_NULLABLE = 'nonNullable';
    public const IS_ARRAY = 'isArray';
    public const IS_NON_NULLABLE_ITEMS_IN_ARRAY = 'isNonNullableItemsInArray';
    public const IS_ARRAY_OF_ARRAYS = 'isArrayOfArrays';
    public const IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS = 'isNonNullableItemsInArrayOfArrays';
    public const DESCRIPTION = 'description';
    public const SPECIFIED_BY_URL = 'specifiedByURL';
    public const VERSION = 'version';
    public const VERSION_CONSTRAINT = 'versionConstraint';
    public const MANDATORY = 'mandatory';
    public const ITEMS = 'items';
    public const DEPRECATED = 'deprecated';
    public const DEPRECATION_MESSAGE = 'deprecationMessage';
    public const VALUE = 'value';
    public const DEFAULT_VALUE = 'defaultValue';
    public const ARGS = 'args';
    public const ORDERED_ARGS_ENABLED = 'orderedArgsEnabled';
    public const INTERFACES = 'interfaces';
    public const FIELDS = 'fields';
    public const GLOBAL_FIELDS = 'globalFields';
    public const QUERY_TYPE = 'queryType';
    public const TYPES = 'types';
    public const POSSIBLE_TYPES = 'possibleTypes';
    public const DIRECTIVES = 'directives';
    public const GLOBAL_DIRECTIVES = 'globalDirectives';
    public const FIELD_IS_MUTATION = 'isMutation';
    public const DIRECTIVE_TYPE = 'directiveType';
    public const DIRECTIVE_PIPELINE_POSITION = 'pipelinePosition';
    public const DIRECTIVE_IS_REPEATABLE = 'isRepeatable';
    public const DIRECTIVE_IS_GLOBAL = 'isGlobal';
    public const DIRECTIVE_NEEDS_DATA_TO_EXECUTE = 'needsDataToExecute';
    public const DIRECTIVE_LIMITED_TO_FIELDS = 'limitedToFields';
    public const DIRECTIVE_EXPRESSIONS = 'expressions';
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

class SchemaDefinition
{
    public final const NAME = 'name';
    public final const NAMESPACED_NAME = 'namespacedName';
    public final const ELEMENT_NAME = 'elementName';
    public final const TYPE_RESOLVER = 'typeResolver';
    public final const NON_NULLABLE = 'nonNullable';
    public final const IS_ARRAY = 'isArray';
    public final const IS_NON_NULLABLE_ITEMS_IN_ARRAY = 'isNonNullableItemsInArray';
    public final const IS_ARRAY_OF_ARRAYS = 'isArrayOfArrays';
    public final const IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS = 'isNonNullableItemsInArrayOfArrays';
    public final const DESCRIPTION = 'description';
    public final const SPECIFIED_BY_URL = 'specifiedByURL';
    public final const VERSION = 'version';
    public final const VERSION_CONSTRAINT = 'versionConstraint';
    public final const MANDATORY = 'mandatory';
    public final const ITEMS = 'items';
    public final const DEPRECATED = 'deprecated';
    public final const DEPRECATION_MESSAGE = 'deprecationMessage';
    public final const VALUE = 'value';
    public final const DEFAULT_VALUE = 'defaultValue';
    public final const ARGS = 'args';
    public final const EXTENSIONS = 'extensions';
    public final const ORDERED_ARGS_ENABLED = 'orderedArgsEnabled';
    public final const INTERFACES = 'interfaces';
    public final const FIELDS = 'fields';
    public final const INPUT_FIELDS = 'inputFields';
    public final const GLOBAL_FIELDS = 'globalFields';
    public final const QUERY_TYPE = 'queryType';
    public final const TYPES = 'types';
    public final const POSSIBLE_TYPES = 'possibleTypes';
    public final const DIRECTIVES = 'directives';
    public final const GLOBAL_DIRECTIVES = 'globalDirectives';
    public final const IS_ADMIN_ELEMENT = 'isAdminElement';
    public final const FIELD_IS_MUTATION = 'isMutation';
    public final const DIRECTIVE_KIND = 'directiveKind';
    public final const DIRECTIVE_PIPELINE_POSITION = 'pipelinePosition';
    public final const DIRECTIVE_IS_REPEATABLE = 'isRepeatable';
    public final const DIRECTIVE_IS_GLOBAL = 'isGlobal';
    public final const DIRECTIVE_NEEDS_DATA_TO_EXECUTE = 'needsDataToExecute';
    public final const DIRECTIVE_LIMITED_TO_FIELDS = 'limitedToFields';
    public final const DIRECTIVE_EXPRESSIONS = 'expressions';
    public final const SCHEMA_IS_NAMESPACED = 'isSchemaNamespaced';
}

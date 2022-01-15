<?php

declare(strict_types=1);

namespace PoP\Engine;

class Environment
{
    public const DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS = 'DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS';
    public const ENABLE_PASSING_EXPRESSIONS_BY_ARG_IN_NESTED_DIRECTIVES = 'ENABLE_PASSING_EXPRESSIONS_BY_ARG_IN_NESTED_DIRECTIVES';
    public const ENABLE_QUERYING_APP_STATE_FIELDS = 'ENABLE_QUERYING_APP_STATE_FIELDS';

    public static function disablePersistingDefinitionsOnEachRequest(): bool
    {
        return getenv('DISABLE_PERSISTING_DEFINITIONS_ON_EACH_REQUEST') !== false ? strtolower(getenv('DISABLE_PERSISTING_DEFINITIONS_ON_EACH_REQUEST')) == "true" : false;
    }
}

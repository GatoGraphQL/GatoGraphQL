<?php

declare(strict_types=1);

namespace PoP\Engine;

class Environment
{
    public const ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE = 'ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE';
    public const DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS = 'DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS';

    public static function disablePersistingDefinitionsOnEachRequest(): bool
    {
        return getenv('DISABLE_PERSISTING_DEFINITIONS_ON_EACH_REQUEST') !== false ? strtolower(getenv('DISABLE_PERSISTING_DEFINITIONS_ON_EACH_REQUEST')) == "true" : false;
    }

    public static function disableGuzzleOperators(): bool
    {
        return getenv('DISABLE_GUZZLE_OPERATORS') !== false ? strtolower(getenv('DISABLE_GUZZLE_OPERATORS')) == "true" : false;
    }
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

class ModuleRegistryTokens
{
    /**
     * Indicate that a module is dependent on another module being disabled
     * (not enabled, as is the normal behavior)
     */
    public final const INVERSE_DEPENDENCY = '!';
}

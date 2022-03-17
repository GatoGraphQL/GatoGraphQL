<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution\DynamicVariables;

class DynamicVariablesEnabledTest extends AbstractDynamicVariablesTest
{
    protected static function enabled(): bool
    {
        return true;
    }
}

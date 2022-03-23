<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ResolvedFieldVariableReferences;

class ResolvedFieldVariableReferencesDisabledTest extends AbstractResolvedFieldVariableReferencesTest
{
    protected static function enabled(): bool
    {
        return false;
    }
}

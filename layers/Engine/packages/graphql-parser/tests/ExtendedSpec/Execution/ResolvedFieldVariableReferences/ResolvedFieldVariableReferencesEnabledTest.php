<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution\ResolvedFieldVariableReferences;

class ResolvedFieldVariableReferencesEnabledTest extends AbstractResolvedFieldVariableReferencesTest
{
    protected static function enabled(): bool
    {
        return true;
    }
}

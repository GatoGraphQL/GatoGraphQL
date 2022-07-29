<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedFieldVariableReferences;

class ObjectResolvedFieldVariableReferencesEnabledTest extends AbstractObjectResolvedFieldVariableReferencesTest
{
    protected static function enabled(): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedFieldVariableReferences;

class ObjectResolvedFieldVariableReferencesDisabledTest extends AbstractObjectResolvedFieldVariableReferencesTest
{
    protected static function enabled(): bool
    {
        return false;
    }
}

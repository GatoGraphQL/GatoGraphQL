<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedFieldValueReferences;

class ObjectResolvedFieldValueReferencesDisabledTest extends AbstractObjectResolvedFieldValueReferencesTest
{
    protected static function enabled(): bool
    {
        return false;
    }
}

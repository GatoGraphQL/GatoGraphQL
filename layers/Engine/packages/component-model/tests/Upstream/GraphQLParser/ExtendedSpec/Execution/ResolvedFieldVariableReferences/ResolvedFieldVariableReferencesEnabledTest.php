<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedFieldValueReferences;

class ObjectResolvedFieldValueReferencesEnabledTest extends AbstractObjectResolvedFieldValueReferencesTest
{
    protected static function enabled(): bool
    {
        return true;
    }
}

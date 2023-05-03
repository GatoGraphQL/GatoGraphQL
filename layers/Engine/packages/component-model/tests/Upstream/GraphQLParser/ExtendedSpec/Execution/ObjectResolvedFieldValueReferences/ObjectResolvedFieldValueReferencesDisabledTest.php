<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedFieldValueReferences;

class ObjectResolvedFieldValueReferencesDisabledTest extends AbstractObjectResolvedFieldValueReferencesTestCase
{
    protected static function enabled(): bool
    {
        return false;
    }
}

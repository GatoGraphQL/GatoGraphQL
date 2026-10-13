<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline\SpecCompliantErrorHandling;

class DirectiveNullabilityValidationDisabledTest extends AbstractDirectiveNullabilityValidationTestCase
{
    protected static function enabled(): bool
    {
        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline\SpecCompliantErrorHandling;

class DirectiveNullabilityValidationEnabledTest extends AbstractDirectiveNullabilityValidationTestCase
{
    protected static function enabled(): bool
    {
        return true;
    }
}

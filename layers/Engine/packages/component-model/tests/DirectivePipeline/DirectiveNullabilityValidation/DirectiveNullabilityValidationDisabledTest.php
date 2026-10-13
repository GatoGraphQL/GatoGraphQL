<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline\DirectiveNullabilityValidation;

class DirectiveNullabilityValidationDisabledTest extends AbstractDirectiveNullabilityValidationTestCase
{
    protected static function enabled(): bool
    {
        return false;
    }
}

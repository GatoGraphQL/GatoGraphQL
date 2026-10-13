<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline\DirectiveNullabilityValidation;

class DirectiveNullabilityValidationEnabledTest extends AbstractDirectiveNullabilityValidationTestCase
{
    protected static function enabled(): bool
    {
        return true;
    }
}

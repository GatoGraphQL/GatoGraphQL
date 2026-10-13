<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\Enums\OutputValueNullabilityViolations;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

/**
 * A directive left a Non-Null response position as `null`,
 * without adding an error for it.
 */
class DirectiveNullabilityViolation
{
    public function __construct(
        public readonly Directive $directive,
        public readonly bool $isMetaDirective,
        public readonly FieldInterface $field,
        public readonly OutputValueNullabilityViolations $nullabilityViolation,
    ) {
    }
}

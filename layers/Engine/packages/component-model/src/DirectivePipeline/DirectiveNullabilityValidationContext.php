<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

/**
 * State of the directive pipeline started by the engine,
 * to report the nullability violations once all its
 * directives have been executed.
 */
class DirectiveNullabilityValidationContext
{
    /**
     * @var array<string|int,array<int,DirectiveNullabilityViolation>> Key: object ID => spl_object_id of the field
     */
    public array $idFieldNullabilityViolations = [];

    public function __construct(
        public readonly int $objectErrorCount,
        public readonly int $schemaErrorCount,
    ) {
    }
}

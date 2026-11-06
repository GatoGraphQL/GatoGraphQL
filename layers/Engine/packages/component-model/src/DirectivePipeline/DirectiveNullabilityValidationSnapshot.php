<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\Enums\OutputValueNullabilityViolations;

/**
 * State of a directive pipeline stage before the directive
 * is executed, to compare against after it has been executed.
 */
class DirectiveNullabilityValidationSnapshot
{
    /**
     * @param array<string|int,array<int,OutputValueNullabilityViolations|null>> $idFieldNullabilityViolations Key: object ID => spl_object_id of the field => violation. A missing field entry means the field had no value.
     */
    public function __construct(
        public readonly int $objectErrorCount,
        public readonly int $schemaErrorCount,
        public readonly array $idFieldNullabilityViolations,
    ) {
    }
}

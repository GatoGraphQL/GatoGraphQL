<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

/**
 * Defines the constants indicating where to place a directive in the directive execution pipeline
 * 2 directives are mandatory: Validate and ResolveAndMerge, which are executed in this order.
 * All other directives must indicate where to position themselves, using these 2 directives as anchors.
 * There are 5 positions:
 * 1. At the beginning
 * 2. Before the Validate pipeline
 * 3. Between the Validate and Resolve directives
 * 4. After the ResolveAndMerge directive
 * 5. At the end
 */
class PipelinePositions
{
    public const BEGINNING = 'beginning';
    public const BEFORE_VALIDATE = 'before-validate';
    public const AFTER_VALIDATE_BEFORE_RESOLVE = 'after-validate-before-resolve';
    public const AFTER_RESOLVE = 'after-resolve';
    public const END = 'end';
}

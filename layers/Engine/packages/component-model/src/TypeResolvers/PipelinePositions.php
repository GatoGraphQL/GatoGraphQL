<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

/**
 * Define where to place the directive in the directive execution pipeline
 *
 * 3 directives are mandatory, and executed in this order:
 *
 *   1. Validate: to validate that the schema, fieldNames, etc are supported, and filter them out if not
 *   2. ResolveAndMerge: to resolve the field and place the data into the DB object
 *   3. SerializeLeafOutputTypeValues: to serialize Scalar and Enum Type values
 * 
 * All other directives must indicate where to position themselves,
 * using these 3 directives as anchors.
 *
 * There are 6 positions:
 *
 *   1. At the very beginning
 *   2. Before Validate directive
 *   3. Between the Validate and Resolve directives
 *   4. Between the Resolve and Serialize directives
 *   5. After the Serialize directive
 *   6. At the very end
 *
 * In the "serialize" step, the directive takes the objects
 * stored in $resolvedIDFieldValues, such as a DateTime object,
 * and converts them to string for printing in the response.
 */
class PipelinePositions
{
    public final const BEGINNING = 'beginning';
    public final const BEFORE_VALIDATE = 'before-validate';
    public final const AFTER_VALIDATE_BEFORE_RESOLVE = 'after-validate-before-resolve';
    public final const AFTER_RESOLVE_BEFORE_SERIALIZE = 'after-resolve-before-serialize';
    public final const AFTER_SERIALIZE = 'after-serialize';
    public final const END = 'end';
}

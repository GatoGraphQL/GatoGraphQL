<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use stdClass;

/**
 * Oneof InputObject Type, as proposed for the GraphQL spec:
 *
 * @see https://github.com/graphql/graphql-spec/pull/825
 */
abstract class AbstractOneofQueryableInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver implements OneofInputObjectTypeResolverInterface
{
    use OneofInputObjectTypeResolverTrait;

    /**
     * Validate that there is exactly one input set
     */
    protected function coerceInputObjectValue(
        stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): ?stdClass {
        $this->validateOneofInputObjectValue($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        return parent::coerceInputObjectValue($inputValue, $schemaInputValidationFeedbackStore);
    }
}

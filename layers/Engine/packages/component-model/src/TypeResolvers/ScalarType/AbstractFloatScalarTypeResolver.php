<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use CastToType;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
abstract class AbstractFloatScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrors();
        $this->validateIsNotStdClass($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() > $errorCount) {
            return null;
        }
        /** @var string|int|float|bool $inputValue */

        $castInputValue = CastToType::_float($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return (float) $castInputValue;
    }
}

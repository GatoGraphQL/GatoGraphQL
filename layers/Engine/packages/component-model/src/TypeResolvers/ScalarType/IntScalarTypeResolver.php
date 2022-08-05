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
class IntScalarTypeResolver extends AbstractScalarTypeResolver
{
    use BuiltInScalarTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'Int';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The Int scalar type represents non-fractional signed whole numeric values.', 'component-model');
    }

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

        $castInputValue = CastToType::_int($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return (int) $castInputValue;
    }
}

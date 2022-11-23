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
class BooleanScalarTypeResolver extends AbstractScalarTypeResolver
{
    use BuiltInScalarTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'Boolean';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsNotStdClass($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }
        /** @var string|int|float|bool $inputValue */

        /**
         * Watch out! In Library CastToType, an empty string is not false, but it's NULL
         * But for us it must be false, since calling query ?query=and([true,false]) gets transformed to the $field string "[1,]"
         */
        if ($inputValue === '') {
            return false;
        }

        $castInputValue = CastToType::_bool($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return (bool) $castInputValue;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The Boolean scalar type represents `true` or `false`.', 'component-model');
    }
}

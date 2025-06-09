<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * A non-empty string scalar type that validates the string is not empty.
 */
class NonEmptyStringScalarTypeResolver extends StringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'NonEmptyString';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A non-empty string scalar type that validates the string is not empty.', 'extended-schema-commons');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $castInputValue = parent::coerceValue(
            $inputValue,
            $astNode,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($castInputValue === null) {
            return null;
        }
        /** @var string $castInputValue */
        if (trim($castInputValue) === '') {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return $castInputValue;
    }
} 
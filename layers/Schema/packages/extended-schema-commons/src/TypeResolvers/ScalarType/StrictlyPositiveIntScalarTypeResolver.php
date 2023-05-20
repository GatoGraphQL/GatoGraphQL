<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractIntScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class StrictlyPositiveIntScalarTypeResolver extends AbstractIntScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'StrictlyPositiveInt';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A positive integer (> 0).', 'extended-schema-commons');
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
        /** @var int $castInputValue */
        if ($castInputValue <= 0) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return $castInputValue;
    }
}

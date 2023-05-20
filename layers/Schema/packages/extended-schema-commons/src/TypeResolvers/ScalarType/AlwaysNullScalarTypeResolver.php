<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class AlwaysNullScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AlwaysNull';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('"Always `null`" scalar, to indicate the the field will always be resolved as `null`', 'extended-schema-commons');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        return null;
    }
}

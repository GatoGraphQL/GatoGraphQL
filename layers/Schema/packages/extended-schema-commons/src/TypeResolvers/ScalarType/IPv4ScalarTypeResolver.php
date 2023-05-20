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
class IPv4ScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'IPv4';
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc4001#section-3';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('IPv4 scalar, such as 192.168.0.1', 'extended-schema-commons');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsString($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }
        /** @var string $inputValue */

        $this->validateFilterVar($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore, \FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        return $inputValue;
    }
}

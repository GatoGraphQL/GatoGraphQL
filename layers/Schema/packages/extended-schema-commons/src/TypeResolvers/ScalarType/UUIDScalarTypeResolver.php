<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoPSchema\ExtendedSchemaCommons\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class UUIDScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'UUID';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('UUID (universally unique identifier) scalar, such as 25770975-0c3d-4ff0-ba27-a0f98fe9b052', 'extended-schema-commons');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc4122';
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

        if (\preg_match('/^{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}}?$/', $inputValue) !== 1) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E2,
                        [
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $astNode,
                ),
            );
            return null;
        }
        return $inputValue;
    }
}

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
class PhoneNumberScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'PhoneNumber';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Phone number scalar, such as +1-212-555-0149', 'extended-schema-commons');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc3966#section-5.1';
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

        if (\preg_match('/(\+{1}[0-9]{1,3}[0-9]{8,9})/', $inputValue) !== 1) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E1,
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
